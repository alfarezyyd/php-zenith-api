<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class Address extends Model
  {
    use HasFactory;

    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
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

    public function user(): BelongsTo{
      return $this->belongsTo(User::class, 'address_id','id');
    }

    public function expeditionProvince(): BelongsTo{
      return $this->belongsTo(ExpeditionProvince::class, 'expedition_province_id','id');
    }

    public function expeditionCity(): BelongsTo{
      return $this->belongsTo(ExpeditionCity::class, 'expedition_city_id','id');
    }
  }
