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
        "neighbourhood_number" => $this['neighbourhood_number'],
        "hamlet_number" => $this['hamlet_number'],
        "village" => $this['village'],
        "urban_village" => $this['urban_village'],
        "sub_district" => $this['sub_district'],
        "postal_code" => $this['postal_code'],
        "note" => $this['note'],
        "receiver_name" => $this['receiver_name'],
        "telephone" => $this['telephone'],
      ];
    }
  }
