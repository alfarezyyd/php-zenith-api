<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\StoreSaveRequest;
  use App\Models\Store;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Storage;

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
     * @throws HttpResponseException
     */
    public function store(StoreSaveRequest $storeSaveRequest): \Illuminate\Http\JsonResponse
    {
      $validatedStoreSaveRequest = $storeSaveRequest->validated();
      $validatedStoreSaveRequest['user_id'] = Auth::id();
      $uploadedFile = $storeSaveRequest->file("photo");
      try {
        DB::beginTransaction();
        $uploadedFile->storePubliclyAs("stores", $uploadedFile->getClientOriginalName(), "public");
        $validatedStoreSaveRequest['image_path'] = "stores/" . $uploadedFile->getClientOriginalName();
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
        ->json((new WebResponsePayload("Store created successfully"))
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
    public function update(StoreSaveRequest $storeSaveRequest, int $storeId): \Illuminate\Http\JsonResponse
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
    public function destroy(int $storeId)
    {
      $loggedUserId = Auth::id();
      $storeModel = Store::query()->findOrFail($storeId)->where('user_id', $loggedUserId)->delete();
      return response()
        ->json((new WebResponsePayload("Store deleted successfully"))
          ->getJsonResource())->setStatusCode(200);
    }
  }
