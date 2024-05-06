<?php

  namespace App\Repository\Contract;

  use Illuminate\Database\Eloquent\Collection;

  interface SearchRepository
  {
    public function search(string $query): Collection;
  }
