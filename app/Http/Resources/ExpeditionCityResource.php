<?php

  namespace App\Http\Resources;

  use App\Models\ExpeditionProvince;
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class ExpeditionCityResource extends JsonResource
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
        'type' => $this['type'],
        'name' => $this['name'],
        'postal_code' => $this['postal_code'],
      ];
    }
  }
