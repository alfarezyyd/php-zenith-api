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
        'id' => optional($this->resource)['id'],
        'name' => optional($this->resource)['name'],
        'slug' => optional($this->resource)['slug'],
        'domain' => optional($this->resource)['domain'],
        'slogan' => optional($this->resource)['slogan'],
        'location_name' => optional($this->resource)['location_name'],
        'city' => optional($this->resource)['city'],
        'zip_code' => optional($this->resource)['zip_code'],
        'detail' => optional($this->resource)['detail'],
        'description' => optional($this->resource)['description'],
        'image_path' => optional($this->resource)['image_path'],
        'products' => ProductResource::collection($this->whenLoaded('products')),
      ];
    }
  }
