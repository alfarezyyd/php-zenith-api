<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\CategoryCreateRequest;
  use App\Http\Requests\CategoryUpdateRequest;
  use App\Models\Category;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;

  class CategoryController extends Controller
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
     * @throws \HttpResponseException
     */
    public function store(CategoryCreateRequest $categoryCreateRequest): JsonResponse
    {
      $validatedCategorySaveRequest = $categoryCreateRequest->validated();
      $categoryModel = new Category($validatedCategorySaveRequest);
      $saveState = $categoryModel->save($validatedCategorySaveRequest);
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
    public function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $categoryUpdateRequest, int $categoryId): JsonResponse
    {
      $validatedCategorySaveRequest = $categoryUpdateRequest->validated();
      $categoryModel = Category::query()->findOrFail($categoryId);
      $categoryModel->fill($validatedCategorySaveRequest);
      $categoryModel->save();
      return response()
        ->json((new WebResponsePayload("Category updated successfully"))
          ->getJsonResource());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $categoryId): JsonResponse
    {
      Category::query()->findOrFail($categoryId)->delete();
      return response()
        ->json((new WebResponsePayload("Category deleted successfully"))
          ->getJsonResource());
    }
  }
