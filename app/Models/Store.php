<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class Store extends Model
  {
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'slug',
      'name',
      'domain',
      'slogan',
      'location_name',
      'city',
      'zip_code',
      'detail',
      'description',
      'image_path',
      'user_id'
    ];

    public function products(): HasMany
    {
      return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function carts(): BelongsToMany
    {
      return $this->belongsToMany(Cart::class, 'product_carts', 'store_id', 'cart_id')
        ->using(ProductCart::class);
    }
  }
