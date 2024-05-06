<?php

  namespace App\Repository\Implementation;

  use App\Models\Product;
  use App\Repository\Contract\SearchRepository;
  use Illuminate\Database\Eloquent\Collection;

  class ProductSearchRepository implements SearchRepository
  {
    public function search(string $query): Collection
    {
      return Product::query()
        ->where(fn($query) => (
        $query->where('name', 'LIKE', "%{$query}%")
          ->orWhere('slug', 'LIKE', "%{$query}%")
        ))
        ->get();
    }
  }
