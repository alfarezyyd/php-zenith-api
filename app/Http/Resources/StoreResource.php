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
        'id' =>  $this['id'],
        'name' => $this['name'],
        'slug' => $this['slug'],
        'domain' => $this['domain'],
        'slogan' => $this['slogan'],
        'location_name' => $this['location_name'],
        'city' => $this['city'],
        'zip_code' => $this['zip_code'],
        'detail' => $this['detail'],
        'description' => $this['description'],
        'image_path' => $this['image_path'],
        'products' => ProductResource::collection($this->whenLoaded('products')),
      ];
    }
  }
