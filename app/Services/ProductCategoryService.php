<?php

  namespace App\Services;

  use App\Models\Category;
  use App\Models\Product;

  class ProductCategoryService
  {
    public function store(array $categoryIds, Product $productModel): void
    {
      foreach ($categoryIds as $categoryId) {
        $productModel->categories()->attach($categoryId);
      }
    }

    public function destroy(int $productId, int $categoryId): void
    {
      $productModel = Product::query()->findOrFail($productId);
      $categoryModel = Category::query()->findOrFail($categoryId);
      $productModel->categories()->detach($categoryModel);
    }
  }
