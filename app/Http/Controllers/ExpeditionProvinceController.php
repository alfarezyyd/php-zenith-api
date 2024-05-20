<?php

  namespace App\Http\Controllers;

  use App\Http\Resources\ExpeditionProvinceResource;
  use App\Models\ExpeditionProvince;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\Client\ConnectionException;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Http;

  class ExpeditionProvinceController extends Controller
  {
    private ExpeditionProvince $expeditionProvince;

    /**
     * @param ExpeditionProvince $expeditionProvince
     */
    public function __construct(ExpeditionProvince $expeditionProvince)
    {
      $this->expeditionProvince = $expeditionProvince;
    }

    public function index(): JsonResponse
    {
      $expeditionProvinceModels = ExpeditionProvince::query()->with('expeditionCities')->get();

      return response()->json(
        (new WebResponsePayload("Expedition province retrieved succesfully", jsonResource:  ExpeditionProvinceResource::collection($expeditionProvinceModels)))
          ->getJsonResource())
        ->setStatusCode(200);
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
  }
