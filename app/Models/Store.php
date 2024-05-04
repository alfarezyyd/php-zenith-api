<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\HasMany;

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
      'image_path',
      'user_id'
    ];

    public function products(): HasMany{
      return $this->hasMany(Product::class, 'store_id', 'id');
    }
  }
