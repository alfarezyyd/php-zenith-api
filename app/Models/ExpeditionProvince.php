<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\HasMany;

  class ExpeditionProvince extends Model
  {
    use HasFactory;

    protected $table = 'expedition_provinces';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'id',
      'name',
      'created_at',
      'updated_at'
    ];

    public function addresses(): HasMany
    {
      return $this->hasMany(Address::class, 'address_id', 'id');
    }

    public function expeditionCity(): HasMany
    {
      return $this->hasMany(ExpeditionCity::class, 'expedition_province_id', 'id');
    }
  }
