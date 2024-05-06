<?php

  namespace App\Http\Resources;

  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class StoreResource extends JsonResource
  {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'name' => $this['name'],
        'domain' => $this['domain'],
        'slogan' => $this['slogam'],
        'location_name' => $this['location_name'],
        'city' => $this['city'],
        'zip_code' => $this['zip_code'],
        'detail' => $this['detail'],
        'description' => $this['description'],
        'image_path' => $this['image_path'],
        'products' => new ProductResource($this->whenLoaded('products')),
      ];
    }
  }
