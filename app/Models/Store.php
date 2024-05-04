<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class Store extends Model
  {
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'name',
      'domain',
      'slogan',
      'location_name',
      'city',
      'zip_code',
      'detail',
      'description',
      'photo_path',
      'user_id'
    ];
  }
