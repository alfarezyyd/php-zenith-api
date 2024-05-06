<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

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
  }
