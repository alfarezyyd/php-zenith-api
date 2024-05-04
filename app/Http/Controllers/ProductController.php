<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ProductSaveRequest;
  use App\Models\Product;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class ProductController extends Controller
  {
    private CommonHelper $commonHelper;

    /**
     * @param CommonHelper $commonHelper
     */
    public function __construct(CommonHelper $commonHelper)
    {
      $this->commonHelper = $commonHelper;
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
      $productModel = new Product($validatedProductSaveRequest);
      $saveState = $productModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Category created successfully"))
          ->getJsonResource())->setStatusCode(201);
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
