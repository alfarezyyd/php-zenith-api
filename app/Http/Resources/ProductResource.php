<?php

  namespace App\Http\Resources;

  use App\Models\Store;
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class ProductResource extends JsonResource
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
        'slug' => optional($this->resource)['slug'],
        'name' => optional($this->resource)['name'],
        'condition' => optional($this->resource)['condition'],
        'description' => optional($this->resource)['description'],
        'price' => optional($this->resource)['price'],
        'minimum_order' => optional($this->resource)['minimum_order'],
        'status' => optional($this->resource)['status'],
        'stock' => optional($this->resource)['stock'],
        'sku' => optional($this->resource)['sku'],
        'weight' => optional($this->resource)['weight'],
        'width' => optional($this->resource)['width'],
        'height' => optional($this->resource)['height'],
        'store_id' => optional($this->resource)['store_id'],
        'category' => CategoryResource::make(optional($this->resource))['categories'],
        'store' => StoreResource::make($this->whenLoaded('store')),
        'resources' => ProductResourceResource::collection($this->whenLoaded('resources'))
      ];
    }
  }
