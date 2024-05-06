<?php

  namespace App\Models;

  use App\Enums\ExpeditionCityType;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class ExpeditionCity extends Model
  {
    use HasFactory;

    protected $table = 'expedition_cities';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $casts = [
      'expedition_province_id' => ExpeditionCityType::class
    ];

    protected $fillable = [
      'id',
      'type',
      'name',
      'postal_code',
      'expedition_province_id'
    ];
  }