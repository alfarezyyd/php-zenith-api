<?php

  namespace App\Http\Controllers\Auth;

  use App\Http\Controllers\Controller;
  use App\Models\SocialAccount;
  use App\Models\User;
  use App\Payloads\WebResponsePayload;
  use App\Services\CartService;
  use Exception;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Request;
  use Laravel\Socialite\Facades\Socialite;
  use Symfony\Component\HttpFoundation\RedirectResponse;

  class SocialiteController extends Controller
  {
    private CartService $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
      $this->cartService = $cartService;
    }


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
      $plainTextToken = $authUser->createToken('login_token')->plainTextToken;
      Auth()->login($authUser, true);
      return redirect()->to(env('NEXT_WEB_CLIENT_URL') . 'callback?token=' . $plainTextToken);
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
          return $socialAccount->user;
          // return user
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
            $this->cartService->store($userModel['id']);
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

    public function logoutHandler(Request $request)
    {

    }
  }
