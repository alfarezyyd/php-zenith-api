<?php

  namespace App\Services;

  use App\Models\ProductResource;
  use Illuminate\Http\UploadedFile;
  use Illuminate\Support\Str;

  class ProductResourceService
  {
    public function store(array $uploadedFiles, int $productId): void
    {
      $productResources = [];
      foreach ($uploadedFiles as $uploadedFile) {
        $productResources[] = [
          'image_path' => "stores/{$productId}/" . Str::uuid() . "_{$uploadedFile->getClientOriginalName()}",
          'product_id' => $productId
        ];
      }
      ProductResource::query()->insert($productResources);
    }
  }
