<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class Product extends Model
  {
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $with = ['store', 'resources'];
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

    public function categories(): BelongsToMany
    {
      return $this->belongsToMany(Category::class, "product_categories", "product_id", "category_id");
    }

    public function resources(): HasMany
    {
      return $this->hasMany(ProductResource::class, 'product_id', 'id');
    }

    public function store(): BelongsTo
    {
      return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function wishlists(): BelongsToMany
    {
      return $this->belongsToMany(Wishlist::class, 'product_wishlists', 'product_id', 'wishlist_id');
    }

    public function carts(): BelongsToMany
    {
      return $this->belongsToMany(Cart::class, 'product_carts', 'product_id', 'cart_id');
    }
  }
