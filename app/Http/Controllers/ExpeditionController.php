<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ExpeditionSaveRequest;
  use App\Http\Resources\ExpeditionResource;
  use App\Models\Expedition;
  use App\Models\ExpeditionCity;
  use App\Models\ExpeditionProvince;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\Client\ConnectionException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\Http;

  class ExpeditionController extends Controller
  {
    private ExpeditionProvince $expeditionProvince;
    private ExpeditionCity $expeditionCity;
    private CommonHelper $commonHelper;

    /**
     * @param ExpeditionProvinceController $expeditionProvince
     * @param ExpeditionCityController $expeditionCity
     * @param CommonHelper $commonHelper
     */
    public function __construct(ExpeditionProvince $expeditionProvince, ExpeditionCity $expeditionCity, CommonHelper $commonHelper)
    {
      $this->expeditionProvince = $expeditionProvince;
      $this->expeditionCity = $expeditionCity;
      $this->commonHelper = $commonHelper;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $expeditionsModel = Expedition::query()->get();
      return response()
        ->json((new WebResponsePayload("Expedition retrieve successfully", jsonResource: ExpeditionResource::collection($expeditionsModel)))
          ->getJsonResource())->setStatusCode(200);
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
    public function store(ExpeditionSaveRequest $expeditionSaveRequest): JsonResponse
    {
      $validatedExpeditionSaveRequest = $expeditionSaveRequest->validated();
      $expeditionModel = new Expedition($validatedExpeditionSaveRequest);
      $saveState = $expeditionModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Expedition created successfully"))
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
    public function update(ExpeditionSaveRequest $expeditionSaveRequest, int $expeditionId): JsonResponse
    {
      $validatedExpeditionSaveRequest = $expeditionSaveRequest->validated();
      $expeditionModel = Expedition::query()->findOrFail($expeditionId);
      $expeditionModel->fill($validatedExpeditionSaveRequest);
      $expeditionModel->save();
      return response()
        ->json((new WebResponsePayload("Expedition updated successfully"))
          ->getJsonResource());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $expeditionId): JsonResponse
    {
      Expedition::query()->findOrFail($expeditionId)->delete();
      return response()
        ->json((new WebResponsePayload("Expedition deleted successfully"))
          ->getJsonResource());
    }
  }
