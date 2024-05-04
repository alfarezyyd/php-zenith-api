<?php

  namespace App\Services;

  use App\Models\Category;
  use App\Models\Product;

  class ProductCategoryService
  {
    public function store(int $productId, int $categoryId): void
    {
      $productModel = Product::query()->findOrFail($productId);
      $categoryModel = Category::query()->findOrFail($categoryId);
      $productModel->categories()->attach($categoryModel);
    }

    public function destroy(int $productId, int $categoryId): void
    {
      $productModel = Product::query()->findOrFail($productId);
      $categoryModel = Category::query()->findOrFail($categoryId);
      $productModel->categories()->detach($categoryModel);
    }
  }
