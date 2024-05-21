<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\StoreSaveRequest;
  use App\Http\Resources\StoreResource;
  use App\Models\Store;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Str;

  class StoreController extends Controller
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
     */
    public function store(StoreSaveRequest $storeSaveRequest): JsonResponse
    {
      $validatedStoreSaveRequest = $storeSaveRequest->validated();
      $validatedStoreSaveRequest['user_id'] = Auth::id();
      try {
        DB::beginTransaction();
        if (isset($validatedStoreSaveRequest['image'])) {
          $uploadedFile = $validatedStoreSaveRequest['image'];
          $validatedStoreSaveRequest['image_path'] = "stores/" . Str::uuid() . urlencode("_{$uploadedFile->getClientOriginalName()}");
          $uploadedFile->storePubliclyAs("stores", $validatedStoreSaveRequest['image_path'], "public");
        }
        $validatedStoreSaveRequest['slug'] = Str::slug($validatedStoreSaveRequest['name']);
        $storeModel = new Store($validatedStoreSaveRequest);
        $saveState = $storeModel->save();
        $this->commonHelper->validateOperationState($saveState);
        DB::commit();
      } catch (\Exception) {
        Storage::delete("stores/" . $uploadedFile->getClientOriginalName());
        DB::rollBack();
        throw new HttpResponseException("Error occurred when write to DB", 500);
      }
      return response()
        ->json((new WebResponsePayload("Store created successfully", jsonResource: new StoreResource($storeModel)))
          ->getJsonResource())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $storeSlug): JsonResponse
    {
      $storeModel = Store::query()->where('slug', $storeSlug)->with(['products'])->firstOrFail()->getModel();
      return response()
        ->json((new WebResponsePayload("Store retrieved successfully", jsonResource: new StoreResource($storeModel)))
          ->getJsonResource());
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
    public function update(StoreSaveRequest $storeSaveRequest, int $storeId): JsonResponse
    {
      $validatedAddressCreateRequest = $storeSaveRequest->validated();
      $loggedUserId = Auth::id();
      $storeModel = Store::query()->findOrFail($storeId)->where('user_id', $loggedUserId);
      $storeModel->fill($validatedAddressCreateRequest)->save();
      return response()
        ->json((new WebResponsePayload("Store updated successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $storeId): JsonResponse
    {
      $loggedUserId = Auth::id();
      Store::query()->findOrFail($storeId)->where('user_id', $loggedUserId)->delete();
      return response()
        ->json((new WebResponsePayload("Store retrieved successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    public function findByUser(): JsonResponse
    {
      $storeModel = Store::query()->where('user_id', Auth::id())->select(['slug'])->firstOrFail();
      return response()
        ->json((new WebResponsePayload("Product deleted successfully", jsonResource: new StoreResource($storeModel)))
          ->getJsonResource());
    }
  }
