<?php

  namespace App\Http\Controllers;

  use App\Http\Resources\ExpeditionCityResource;
  use App\Models\ExpeditionCity;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\Client\ConnectionException;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Http;

  class ExpeditionCityController extends Controller
  {
    private ExpeditionCity $expeditionCity;

    /**
     * @param ExpeditionCity $expeditionCity
     */
    public function __construct(ExpeditionCity $expeditionCity)
    {
      $this->expeditionCity = $expeditionCity;
    }

    public function index(): JsonResponse
    {
      $expeditionCityModels = ExpeditionCity::query()->get();
      return response()->json(
        (new WebResponsePayload("Expedition city retrieved successfully",
          jsonResource: ExpeditionCityResource::collection($expeditionCityModels))
        )->getJsonResource()
      )->setStatusCode(200);
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
