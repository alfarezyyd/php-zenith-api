<?php

  namespace App\Actions\Fortify;

  use App\Models\Team;
  use App\Models\User;
  use App\Services\CartService;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Hash;
  use Illuminate\Support\Facades\Validator;
  use Laravel\Fortify\Contracts\CreatesNewUsers;
  use Laravel\Jetstream\Jetstream;

  class CreateNewUser implements CreatesNewUsers
  {
    use PasswordValidationRules;

    private CartService $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
      $this->cartService = $cartService;
    }


    /**
     * Create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
      Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => $this->passwordRules(),
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
      ])->validate();

      return DB::transaction(function () use ($input) {
        return tap(User::create([
          'name' => $input['name'],
          'email' => $input['email'],
          'password' => Hash::make($input['password']),
        ]), function (User $user) {
          $this->createTeam($user);
          $this->cartService->store();
        });
      });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
      $user->ownedTeams()->save(Team::forceCreate([
        'user_id' => $user->id,
        'name' => explode(' ', $user->name, 2)[0] . "'s Team",
        'personal_team' => true,
      ]));
    }
  }
