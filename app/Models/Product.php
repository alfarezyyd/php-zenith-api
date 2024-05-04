<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
      'slug',
      'name',
      'condition',
      'description',
      'price',
      'minimum_order',
      'status',
      'stock',
      'sku',
      'weight',
      'width',
      'height',
      'store_id'
    ];
}
