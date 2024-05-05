<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;

  class Cart extends Model
  {
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'user_id'
    ];

    public function products(): BelongsToMany
    {
      return $this->belongsToMany(Product::class, 'product_carts', 'cart_id', 'product_id');
    }
  }
