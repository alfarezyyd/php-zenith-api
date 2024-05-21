<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class OrderResource extends JsonResource
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
        'expedition' => ExpeditionResource::make($this->whenLoaded('expedition')),
        'user' => UserProfileResource::make($this->whenLoaded('user')->profile),
        'address' => AddressResource::make($this->whenLoaded('address')),
        'created_at' => $this['created_at']->format('Y-m-d H:i:s'),
        'updated_at' => $this['updated_at']->format('Y-m-d H:i:s'),
      ];
    }
  }
