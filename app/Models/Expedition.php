<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class Expedition extends Model
  {
    use HasFactory;

    protected $table = 'expeditions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'name'
    ];

    public function Orders(): HasMany
    {
      return $this->hasMany(Order::class, 'expedition_id', 'id');
    }
  }
