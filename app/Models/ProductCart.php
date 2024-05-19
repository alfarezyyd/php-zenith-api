<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\Pivot;

  class ProductCart extends Pivot
  {
    use HasFactory;

    protected $table = 'product_carts';
    protected $foreignKey = 'cart_id';
    protected $relatedKey = 'product_id';
    protected $with = ['product'];
    protected $fillable = [
      'store_id',
      'product_id',
      'cart_id',
      'sub_total_price',
      'quantity',
    ];

    public function product(): BelongsTo
    {
      return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function cart(): BelongsTo
    {
      return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function store(): BelongsTo
    {
      return $this->belongsTo(Store::class, 'store_id', 'id');
    }
  }
