<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ProductSaveRequest;
  use App\Models\Product;
  use App\Payloads\WebResponsePayload;
  use App\Services\ProductResourceService;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;

  class ProductController extends Controller
  {
    private CommonHelper $commonHelper;
    private ProductResourceService $productResourceService;

    /**
     * @param CommonHelper $commonHelper
     * @param ProductResourceService $productResourceService
     */
    public function __construct(CommonHelper $commonHelper, ProductResourceService $productResourceService)
    {
      $this->commonHelper = $commonHelper;
      $this->productResourceService = $productResourceService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      //
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
      $validatedProductSaveRequest['slug'] = $this->commonHelper->slugifyString($validatedProductSaveRequest['name']);
      $productModel = new Product($validatedProductSaveRequest);
      try {
        DB::beginTransaction();
        $productModel->save();
        if ($productSaveRequest->hasFile('images')) {
          $this->productResourceService->store($productSaveRequest->file('images'), $productModel->id);
        }
        DB::commit();
        return response()
          ->json((new WebResponsePayload("Product created successfully"))
            ->getJsonResource())->setStatusCode(201);
      } catch (\Throwable $throwable) {
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
    public function show(string $id)
    {
      //
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
      $productModel = Product::query()->findOrFail($storeId)->where('user_id', Auth::id())->delete();
      return response()
        ->json((new WebResponsePayload("Category deleted successfully"))
          ->getJsonResource());
    }
  }
