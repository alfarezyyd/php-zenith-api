<?php

  namespace App\Http\Controllers\Auth;

  use App\Http\Controllers\Controller;
  use App\Models\SocialAccount;
  use App\Models\User;
  use Exception;
  use Illuminate\Http\JsonResponse;
  use Laravel\Socialite\Facades\Socialite;
  use Symfony\Component\HttpFoundation\RedirectResponse;

  class SocialiteController extends Controller
  {
    public function redirectToProvider($provider): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
      return Socialite::driver($provider)->redirect();
    }

    public function handleProvideCallback($provider): JsonResponse|\Illuminate\Http\RedirectResponse
    {
      try {
        $user = Socialite::driver($provider)->stateless()->user();
      } catch (Exception $e) {
        return redirect()->back();
      }
      // find or create user and send params user get from socialite and provider
      $authUser = $this->findOrCreateUser($user, $provider);

      $token = $authUser->createToken('token_name')->plainTextToken;
      return response()->json(['token' => $token]);
    }

    public function findOrCreateUser($socialUser, $provider)
    {
      // Get Social Account
      $socialAccount = SocialAccount::where('provider_id', $socialUser->getId())
        ->where('provider_name', $provider)
        ->first();

      // Jika sudah ada
      if ($socialAccount) {
        // return user
        return $socialAccount->user;

        // Jika belum ada
      } else {

        // User berdasarkan email
        $user = User::where('email', $socialUser->getEmail())->first();

        // Jika Tidak ada user
        if (!$user) {
          // Create user baru
          $user = User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail()
          ]);
        }

        // Buat Social Account baru
        $user->socialAccounts()->create([
          'provider_id' => $socialUser->getId(),
          'provider_name' => $provider
        ]);

        // return user
        return $user;
      }
    }
  }
