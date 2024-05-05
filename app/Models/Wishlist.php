<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class Wishlist extends Model
  {
    use HasFactory;

    protected $table = 'wishlists';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'user_id',
      'slug',
      'name',
      'description',
      'type'
    ];
  }
