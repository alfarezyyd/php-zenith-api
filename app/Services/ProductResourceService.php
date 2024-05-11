<?php

  namespace App\Services;

  use App\Models\ProductResource;
  use Illuminate\Support\Str;

  class ProductResourceService
  {
    public function store(array $uploadedFiles, int $productId): void
    {
      $productResources = [];
      foreach ($uploadedFiles as $uploadedFile) {
        $imagePath = "stores/{$productId}/" . Str::uuid() . urlencode("_{$uploadedFile->getClientOriginalName()}");
        $productResources[] = [
          'image_path' => $imagePath,
          'product_id' => $productId
        ];
        $uploadedFile->storePubliclyAs("stores", $imagePath, "public");
      }
      ProductResource::query()->insert($productResources);
    }
  }
