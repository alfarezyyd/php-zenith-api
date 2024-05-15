<?php

  namespace App\Http\Resources;

  use App\Models\Product;
  use App\Models\ProductCart;
  use Illuminate\Http\Request;
  use Illuminate\Http\Resources\Json\JsonResource;

  class ProductCartResource extends JsonResource
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
        'sub_total_price' => $this['sub_total_price'],
        'quantity' => $this['quantity'],
        'product' => new ProductResource($this->whenLoaded('product')),
      ];
    }
  }
