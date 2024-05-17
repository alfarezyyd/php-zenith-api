<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Relations\HasMany;
  use Illuminate\Database\Eloquent\Relations\HasOne;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;
  use Illuminate\Support\Facades\DB;

  class User extends Authenticatable
  {
    use HasFactory, Notifiable, HasApiTokens;

    protected $with = ['tokens'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'name',
      'email',
      'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
      'password',
      'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
      return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
      ];
    }

    /**
     * @return HasMany
     */
    public function socialAccounts(): HasMany
    {
      return $this->hasMany(SocialAccount::class);
    }

    public function cart(): HasOne
    {
      return $this->hasOne(Cart::class, 'user_id', 'id');
    }

    public function profile(): HasOne
    {
      return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }
  }
