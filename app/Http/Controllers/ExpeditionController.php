<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ExpeditionCreateRequest;
  use App\Models\Expedition;
  use App\Models\ExpeditionCity;
  use App\Models\ExpeditionProvince;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\Client\ConnectionException;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Http;

  class ExpeditionController extends Controller
  {
    private ExpeditionProvince $expeditionProvince;
    private ExpeditionCity $expeditionCity;
    private CommonHelper $commonHelper;

    /**
     * @param ExpeditionProvince $expeditionProvince
     * @param ExpeditionCity $expeditionCity
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
    public function store(ExpeditionCreateRequest $expeditionCreateRequest): \Illuminate\Http\JsonResponse
    {
      $validatedExpeditionCreateRequest = $expeditionCreateRequest->validated();
      $expeditionModel = new Expedition($validatedExpeditionCreateRequest);
      $saveState = $expeditionModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Expedition created successfully"))
          ->getJsonResource())
        ->setStatusCode(201);
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
    public function update(Request $request, string $id)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      //
    }

    /**
     * Call RAJAONGKIR API for expedition city integration
     *
     * @throws HttpResponseException
     */
    public function syncThirdPartyProvince(): void
    {
      try {
        $responseThirdParty = Http::withHeaders([
          'Accept' => 'application/json',
          'key' => env("RAJAONGKIR_API_KEY")
        ])->get("https://api.rajaongkir.com/starter/province");
        if (!$responseThirdParty->successful()) {
          throw new ConnectionException();
        }
        $responseJson = $responseThirdParty->json();
        $transformedResponseJson = array_map(function ($value) {
          return [
            'id' => $value['province_id'],
            'name' => $value['province'],
            'created_at' => $this->expeditionProvince->freshTimestamp(),
          ];
        }, $responseJson['rajaongkir']['results']);
        ExpeditionProvince::query()->insert($transformedResponseJson);
      } catch (ConnectionException) {
        throw new HttpResponseException(response()->json('Error when trying to connect to third party!', 500));
      }
    }

    /**
     * @throws HttpResponseException
     */
    public function syncThirdPartyCity(): void
    {
      try {
        $responseThirdParty = Http::withHeaders([
          'Accept' => 'application/json',
          'key' => env("RAJAONGKIR_API_KEY")
        ])->get("https://api.rajaongkir.com/starter/city");
        if (!$responseThirdParty->successful()) {
          throw new ConnectionException();
        }
        $responseJson = $responseThirdParty->json();
        $transformedResponseJson = array_map(function ($value) {
          return [
            'id' => $value['city_id'],
            'expedition_province_id' => $value['province_id'],
            'type' => $value['type'],
            'name' => $value['city_name'],
            'postal_code' => $value['postal_code'],
            'created_at' => $this->expeditionCity->freshTimestamp(),
          ];
        }, $responseJson['rajaongkir']['results']);
        ExpeditionCity::query()->insert($transformedResponseJson);
      } catch (ConnectionException) {
        throw new HttpResponseException(response()->json('Error when trying to connect to third party!', 500));
      }
    }
  }
