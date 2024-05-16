<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Relations\HasOne;

  class UserProfile extends Model
  {
    use HasFactory;

    protected $table = 'user_profiles';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
      'user_id',
      'first_name',
      'last_name',
      'email',
      'phone',
      'birth_date',
      'gender',
      'image_path'
    ];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, "user_id", "id");
    }
  }
