<?php

  namespace App\Http\Controllers\Auth;

  use App\Http\Controllers\Controller;
  use App\Models\Cart;
  use App\Models\SocialAccount;
  use App\Models\User;
  use App\Payloads\WebResponsePayload;
  use Exception;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\DB;
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
        $userModel = Socialite::driver($provider)->stateless()->user();
      } catch (Exception $e) {
        return redirect()->back();
      }
      // find or create user and send params user get from socialite and provider
      $authUser = $this->findOrCreateUser($userModel, $provider);

      $token = $authUser->createToken('token_name')->plainTextToken;
      return response()->json(['token' => $token]);
    }

    /**
     * @throws HttpResponseException
     */
    public function findOrCreateUser($socialUser, $provider)
    {
      try {
        DB::beginTransaction();
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
          $userModel = User::where('email', $socialUser->getEmail())->first();

          // Jika Tidak ada user
          if (!$userModel) {
            // Create user baru
            $userModel = User::create([
              'name' => $socialUser->getName(),
              'email' => $socialUser->getEmail()
            ]);
            $cartModel = new Cart(['user_id' => $userModel['id']]);
            $cartModel->save();
          }
          // Buat Social Account baru
          $userModel->socialAccounts()->create([
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider
          ]);
        }
        DB::commit();
        // return user
        return $userModel;
      } catch (Exception) {
        DB::rollBack();
        throw new HttpResponseException(response()->json(
          new WebResponsePayload("Something went wrong when trying to create an account.", 500),
        ));
      }
    }
  }
