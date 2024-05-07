<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;
  use Illuminate\Support\Facades\Auth;

  class AddressResource extends JsonResource
  {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        "id" => $this['id'],
        "label" => $this['label'],
        "street" => $this['street'],
        "neighbourhood_number" => $this['neighbourhoodNumber'],
        "hamlet_number" => $this['hamletNumber'],
        "village" => $this['village'],
        "urban_village" => $this['urbanVillage'],
        "sub_district" => $this['subDistrict'],
        "postal_code" => $this['postalCode'],
        "note" => $this['note'],
        "receiver_name" => $this['receiverName'],
        "telephone" => $this['telephone'],
      ];
    }
  }
