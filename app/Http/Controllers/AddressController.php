<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\AddressSaveRequest;
  use App\Http\Resources\AddressResource;
  use App\Models\Address;
  use App\Models\ExpeditionProvince;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Auth;

  class AddressController extends Controller
  {
    /**
     * @param ExpeditionProvinceController $expeditionProvince
     */
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
    public function index(): JsonResponse
    {
      $addressCollection = Address::query()->select()->where('user_id', Auth::id())->get();
      return response()
        ->json((new WebResponsePayload("Address retrieve successfully", jsonResource: AddressResource::collection($addressCollection)))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     * @throws \HttpResponseException
     */
    public function store(AddressSaveRequest $addressCreateRequest): JsonResponse
    {
      $validatedAddressCreateRequest = $addressCreateRequest->validated();
      $validatedAddressCreateRequest['user_id'] = Auth::id();
      $addressModel = new Address($validatedAddressCreateRequest);
      $saveState = $addressModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Address created successfully"))
          ->getJsonResource())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $addressId): JsonResponse
    {
      $addressModel = Address::query()->findOrFail($addressId)->where('user_id', Auth::id())->get();
      return response()
        ->json((new WebResponsePayload("Address retrieve successfully", jsonResource: new AddressResource($addressModel)))
          ->getJsonResource())->setStatusCode(200);
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
    public function update(AddressSaveRequest $addressCreateRequest, int $addressId): JsonResponse
    {
      $validatedAddressCreateRequest = $addressCreateRequest->validated();
      $validatedAddressCreateRequest['user_id'] = Auth::id();
      Address::query()->findOrFail($addressId)->fill($validatedAddressCreateRequest)->save();
      return response()
        ->json((new WebResponsePayload("Address updated successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $addressId): JsonResponse
    {
      Address::query()->findOrFail($addressId)->where('user_id', Auth::id())->delete();
      return response()
        ->json((new WebResponsePayload("Address deleted successfully"))
          ->getJsonResource())->setStatusCode(200);
    }
  }
