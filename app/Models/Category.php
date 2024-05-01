<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\HasOne;

  class Category extends Model
  {
    use HasFactory;

    protected $fillable = [
      'name',
      'category_id'
    ];

    public function childCategory(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function parentCategory(): BelongsTo
    {
      return $this->belongsTo(Category::class);
    }
  }
