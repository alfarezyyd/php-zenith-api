<?php

  namespace App\Http\Resources;

  use App\Models\ExpeditionCity;
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class ExpeditionProvinceResource extends JsonResource
  {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'id' => $this['id'],
        'name' => $this['name'],
        'expedition_cities' => ExpeditionCityResource::collection(($this->whenLoaded('expeditionCities'))),
      ];
    }
  }
