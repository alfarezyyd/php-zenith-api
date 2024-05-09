<?php

  namespace App\Http\Controllers\Auth;

  use App\Helpers\CommonHelper;
  use App\Http\Controllers\Controller;
  use App\Models\SocialAccount;
  use App\Models\User;
  use App\Payloads\WebResponsePayload;
  use App\Services\CartService;
  use Exception;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Support\Facades\DB;
  use Laravel\Sanctum\NewAccessToken;
  use Laravel\Socialite\Facades\Socialite;
  use Symfony\Component\HttpFoundation\RedirectResponse;

  class SocialiteController extends Controller
  {
    private CartService $cartService;
    private CommonHelper $commonHelper;

    /**
     * @param CartService $cartService
     * @param CommonHelper $commonHelper
     */
    public function __construct(CartService $cartService, CommonHelper $commonHelper)
    {
      $this->cartService = $cartService;
      $this->commonHelper = $commonHelper;
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
      // Periksa jika pengguna sudah memiliki token yang valid
      $token = $authUser->tokens()->where('name', 'login_token')->first();
      if ($token !== null && $this->commonHelper->checkIfExpired($token['expires_at'])) {
        $newAccessToken = new NewAccessToken($token, $token->getKey() . '|' . $authUser->generateTokenString());
        return redirect()->to(env('NEXT_WEB_CLIENT_URL') . "/callback?token={$newAccessToken->plainTextToken}");
      }

      // Buat token baru dan arahkan pengguna ke callback dengan token baru
      $token = $authUser->createToken('login_token', expiresAt: now()->addWeek())->plainTextToken;
      return redirect()->to(env('NEXT_WEB_CLIENT_URL') . "/callback?token={$token}");
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
  }
