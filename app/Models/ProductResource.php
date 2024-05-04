<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class ProductResource extends Model
  {
    use HasFactory;

    protected $table = 'product_resources';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'image_path',
      'video_url',
      'product_id'
    ];

    public function product(): BelongsTo
    {
      return $this->belongsTo(Product::class, 'product_id', 'id');
    }

  }
