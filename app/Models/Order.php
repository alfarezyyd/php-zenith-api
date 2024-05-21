<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class Order extends Model
  {
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'id',
      'total_price',
      'status',
      'address_id',
      'user_id',
      'expedition_id',
      'store_id'
    ];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function expedition(): BelongsTo
    {
      return $this->belongsTo(Expedition::class, 'expedition_id', 'id');
    }

    public function address(): BelongsTo
    {
      return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    public function products(): BelongsToMany
    {
      return $this->belongsToMany(Product::class, 'product_orders', 'order_id', 'product_id')
        ->withPivot('quantity')
        ->using(ProductOrder::class);
    }
  }
