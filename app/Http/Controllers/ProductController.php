<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ProductSaveRequest;
  use App\Http\Resources\ProductResource;
  use App\Http\Resources\StoreResource;
  use App\Models\Category;
  use App\Models\Product;
  use App\Models\Store;
  use App\Payloads\WebResponsePayload;
  use App\Services\ProductCategoryService;
  use App\Services\ProductResourceService;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Str;
  use Throwable;
  use function Ramsey\Uuid\v1;

  class ProductController extends Controller
  {
    private CommonHelper $commonHelper;
    private ProductResourceService $productResourceService;
    private ProductCategoryService $productCategoryService;

    /**
     * @param CommonHelper $commonHelper
     * @param ProductResourceService $productResourceService
     * @param ProductCategoryService $productCategoryService
     */
    public function __construct(CommonHelper $commonHelper, ProductResourceService $productResourceService, ProductCategoryService $productCategoryService)
    {
      $this->commonHelper = $commonHelper;
      $this->productResourceService = $productResourceService;
      $this->productCategoryService = $productCategoryService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

      $productModel = Product::query()->with('store')->get();
      return response()
        ->json((new WebResponsePayload("Product retrieve successfully", jsonResource: ProductResource::collection($productModel)))
          ->getJsonResource())->setStatusCode(200);
    }

    public function indexByCategory(string $categorySlug): JsonResponse
    {
      $categoryModel = Category::query()->where('slug', $categorySlug)->firstOrFail();
      $productsModel = $categoryModel->products->select(['id', 'name', 'slug', 'price', 'condition', 'store']);
      $productsResource = ProductResource::collection($productsModel);

      // Membuat objek WebResponsePayload dengan pesan dan data yang diinginkan
      $webResponse = new WebResponsePayload(
        'Products successfully fetched.',
        jsonResource: $productsResource
      );

      // Mengembalikan respons JSON langsung dengan menggunakan json() method
      return response()->json($webResponse->getJsonResource())->setStatusCode(200);
    }

    public function indexByStore(string $storeSlug)
    {
      $storeModel = Store::query()->where('slug', $storeSlug)->where('id', Auth::id())->firstOrFail();
      $storeModel->load('products');
      return response()->json((new WebResponsePayload(
        'Store retrieved succesfully', jsonResource: new StoreResource($storeModel)
      ))->getJsonResource())->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     * @throws HttpResponseException
     */
    public function store(ProductSaveRequest $productSaveRequest, string $storeSlug): JsonResponse
    {
      $validatedProductSaveRequest = $productSaveRequest->validated();
      $storeModel = Store::query()->where('slug', $storeSlug)->select('id')->firstOrFail();
      $validatedProductSaveRequest['store_id'] = $storeModel['id'];
      $validatedProductSaveRequest['slug'] = Str::slug($validatedProductSaveRequest['name']);
      $productModel = new Product($validatedProductSaveRequest);
      try {
        DB::beginTransaction();
        $productModel->save();
        if ($validatedProductSaveRequest['images'] !== null) {
          $this->productResourceService->store($productSaveRequest->file('images'), $productModel->id);
        }
        $this->productCategoryService->store($validatedProductSaveRequest['category_ids'], $productModel);
        DB::commit();
        return response()
          ->json((new WebResponsePayload("Product created successfully"))
            ->getJsonResource())->setStatusCode(201);
      } catch (Throwable $throwable) {
        DB::rollBack();
        throw new HttpResponseException(response()->json(
          (new WebResponsePayload("Internal server error",))
            ->getJsonResource())->setStatusCode(500)
        );
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $storeSlug, string $productSlug): JsonResponse
    {
      $storeModel = Store::query()->where('slug', $storeSlug)->firstOrFail();
      $productModel = $storeModel->products->where('slug', $productSlug)->firstOrFail();
      return response()
        ->json((new WebResponsePayload("Product retrieved successfully", jsonResource: new ProductResource($productModel)))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     * @throws HttpResponseException|\HttpResponseException
     */
    public function update(ProductSaveRequest $productSaveRequest, int $storeId): JsonResponse
    {
      $productModel = Product::query()->findOrFail($storeId)->where('user_id', Auth::id());
      $validatedProductSaveRequest = $productSaveRequest->validated();
      $productModel->fill($validatedProductSaveRequest);
      $saveState = $productModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Product updated successfully"))
          ->getJsonResource());
    }


    public function destroy(string $storeSlug, int $productId): JsonResponse
    {
      $storeModel = Store::query()->with('products')->where('slug', $storeSlug)->where('user_id', Auth::id())->firstOrFail();
      $storeModel->products->where('product_id', $productId)->firstOrFail()->delete();
      return response()
        ->json((new WebResponsePayload("Product deleted successfully"))
          ->getJsonResource());
    }
  }
