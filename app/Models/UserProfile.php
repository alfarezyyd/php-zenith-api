<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;

  class UserProfile extends Model
  {
    use HasFactory;

    protected $table = 'user_profiles';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'phone',
      'birth_date',
      'gender',
      'image_path'
    ];
  }
