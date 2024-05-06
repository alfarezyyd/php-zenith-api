<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class Order extends Model
  {
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'total_price',
      'status',
      'order_payment_method',
      'address_id',
      'user_id',
      'expedition_id'
    ];
  }
