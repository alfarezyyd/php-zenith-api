<?php

  namespace App\Services;

  use App\Models\User;
  use Illuminate\Support\Facades\DB;
  use Laravel\Sanctum\NewAccessToken;

  class UserService
  {
    public function updateLoginToken(User $authUser): NewAccessToken
    {
      return DB::transaction(function () use ($authUser) {
        $plainTextToken = $authUser->generateTokenString();
        $loginToken = $authUser->tokens->where('name', 'login_token')[0];
        $loginToken->update([
          'token' => hash('sha256', $plainTextToken),
        ]);
        DB::commit();
        return new NewAccessToken($loginToken, $loginToken->getKey() . '|' . $plainTextToken);
      });
    }
  }
