<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ProductSaveRequest;
  use App\Http\Resources\ProductResource;
  use App\Models\Category;
  use App\Models\Product;
  use App\Models\Store;
  use App\Payloads\WebResponsePayload;
  use App\Repository\Contract\SearchRepository;
  use App\Services\ProductCategoryService;
  use App\Services\ProductResourceService;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Str;
  use Throwable;

  class ProductController extends Controller
  {
    private CommonHelper $commonHelper;
    private ProductResourceService $productResourceService;
    private ProductCategoryService $productCategoryService;
    private SearchRepository $searchRepository;

    /**
     * @param CommonHelper $commonHelper
     * @param ProductResourceService $productResourceService
     * @param ProductCategoryService $productCategoryService
     * @param SearchRepository $searchRepository
     */
    public function __construct(CommonHelper $commonHelper, ProductResourceService $productResourceService, ProductCategoryService $productCategoryService, SearchRepository $searchRepository)
    {
      $this->commonHelper = $commonHelper;
      $this->productResourceService = $productResourceService;
      $this->productCategoryService = $productCategoryService;
      $this->searchRepository = $searchRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
      if (request()->has('q')) {
        $productModel = $this->searchRepository->search(request('q'));
      } else {
        $productModel = Product::query()->with(['category', 'store']);
      }
      return response()
        ->json((new WebResponsePayload("Product retrieve successfully", jsonResource: new ProductResource($productModel)))
          ->getJsonResource())->setStatusCode(200);
    }

    public function indexByCategory(string $categorySlug)
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
    public function store(ProductSaveRequest $productSaveRequest, int $storeId): JsonResponse
    {
      $validatedProductSaveRequest = $productSaveRequest->validated();
      $validatedProductSaveRequest['store_id'] = $storeId;
      $validatedProductSaveRequest['slug'] = Str::slug($validatedProductSaveRequest['name']);
      $productModel = new Product($validatedProductSaveRequest);
      try {
        DB::beginTransaction();
        $productModel->save();
        if ($productSaveRequest->hasFile('images')) {
          $this->productResourceService->store($productSaveRequest->file('images'), $productModel->id);
        }
        $this->productCategoryService->store($validatedProductSaveRequest['category_ids'], $productModel);
        DB::commit();
        return response()
          ->json((new WebResponsePayload("Product created successfully"))
            ->getJsonResource())->setStatusCode(201);
      } catch (Throwable $throwable) {
        DB::rollBack();
        print($throwable->getMessage());
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
     * @throws HttpResponseException
     * @throws \HttpResponseException
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $storeId): JsonResponse
    {
      Product::query()->findOrFail($storeId)->where('user_id', Auth::id())->delete();
      return response()
        ->json((new WebResponsePayload("Category deleted successfully"))
          ->getJsonResource());
    }
  }
