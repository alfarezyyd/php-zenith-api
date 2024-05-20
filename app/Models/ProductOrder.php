<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\BelongsToMany;

  class ProductOrder extends Model
  {
    use HasFactory;

    protected $table = 'product_orders';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    public $fillable = [
      'product_id',
      'order_id',
      'quantity',
      'sub_total_price',
      'create_at',
      'updated_at'
    ];

    public function product(): BelongsTo
    {
      return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order(): BelongsTo
    {
      return $this->belongsTo(Order::class, 'order_id', 'id');
    }
  }
