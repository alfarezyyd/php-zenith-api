<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class Address extends Model
  {
    use HasFactory;

    protected $fillable = [
      'label',
      'street',
      'neighbourhood_number',
      'hamlet_number',
      'village',
      'urban_village',
      'sub_district',
      'expedition_city_id',
      'expedition_province_id',
      'postal_code',
      'note',
      'receiver_name',
      'telephone',
      'user_id'
    ];
  }
