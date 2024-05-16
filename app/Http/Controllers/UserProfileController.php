<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\UserProfileCreateRequest;
  use App\Http\Requests\UserProfileUpdateRequest;
  use App\Models\UserProfile;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;

  class UserProfileController extends Controller
  {
    private CommonHelper $commonHelper;

    /**
     * @param CommonHelper $commonHelper
     */
    public function __construct(CommonHelper $commonHelper)
    {
      $this->commonHelper = $commonHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserProfileCreateRequest $userProfileRequest)
    {
      $validatedUserProfileRequest = $userProfileRequest->validated();
      $validatedUserProfileRequest['user_id'] = Auth::id();
      try {
        DB::beginTransaction();
        $userProfileModel = new UserProfile($validatedUserProfileRequest);
        $userProfileModel->save();
        DB::commit();
        return response()->json(
          (new WebResponsePayload("User profile successfully created."))->getJsonResource(),
        )->setStatusCode(201);
      } catch (\Exception $e) {
        DB::rollback();
        throw new HttpResponseException(response()->json(
          (new WebResponsePayload("User profile failed to create", errorInformation: $e->getMessage()))->getJsonResource(),
        )->setStatusCode(500));
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserProfile $userProfile)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProfile $userProfile)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserProfileUpdateRequest $userProfileUpdateRequest, int $userProfileId)
    {
      $userProfileModel = UserProfile::query()->firstOrFail($userProfileId);
      $validatedUserProfileUpdateRequest = $userProfileUpdateRequest->validated();
      $userProfileModel->fill($validatedUserProfileUpdateRequest);
      $userProfileModel->save();
      return response()->json(
        new WebResponsePayload("User profile successfully updated"),
      )->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \HttpResponseException
     */
    public function destroy(int $userProfileId)
    {
      $deleteState = UserProfile::query()->where('id', $userProfileId)->delete();
      $this->commonHelper->validateOperationState($deleteState);
      return response()->json(
        new WebResponsePayload("User profile successfully deleted"),
      )->setStatusCode(200);
    }
  }
