<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class StoreOrderResource extends JsonResource
  {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'id' =>  $this['id'],
        'total_price' => $this['total_price'],
        'status' => $this['status'],
        'expedition' => ExpeditionResource::make($this->whenLoaded('expedition')),
        'user' => UserProfileResource::make($this->whenLoaded('user')->profile),
        'address' => AddressResource::make($this->whenLoaded('address')),
      ];
    }
  }
