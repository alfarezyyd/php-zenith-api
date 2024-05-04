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
        'id' => $this['id'],
        'slug' => optional($this->resource)['slug'],
        'name' => $this['name'],
        'condition' => $this['condition'],
        'description' => optional($this->resource)['description'],
        'price' => $this['price'],
        'minimum_order' => optional($this->resource)['minimum_order'],
        'status' => optional($this->resource)['status'],
        'stock' => optional($this->resource)['stock'],
        'sku' => optional($this->resource)['sku'],
        'weight' => optional($this->resource)['weight'],
        'width' => optional($this->resource)['width'],
        'height' => optional($this->resource)['height'],
        'store' => StoreResource::make(optional($this->resource)['store']),
      ];
    }
  }
