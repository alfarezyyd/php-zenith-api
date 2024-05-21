<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class UserOrderResource extends JsonResource
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
        'total_price' => $this['total_price'],
        'status' => $this['status'],
        'expedition' => ExpeditionResource::make($this->whenLoaded('expedition')),
        'address' => AddressResource::make($this->whenLoaded('address')),
        'receipt_number' => $this['receipt_number'],
        'created_at' => $this['created_at']->addHours(7)->format('Y-m-d H:i:s'),
        'updated_at' => $this['updated_at']->addHours(7)->format('Y-m-d H:i:s'),
      ];
    }
  }
