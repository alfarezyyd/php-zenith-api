<?php

  namespace App\Http\Controllers;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\WishlistSaveRequest;
  use App\Models\Wishlist;
  use App\Payloads\WebResponsePayload;
  use HttpResponseException;
  use Illuminate\Http\JsonResponse;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;

  class WishlistController extends Controller
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
     * @throws HttpResponseException
     */
    public function store(WishlistSaveRequest $wishlistSaveRequest): JsonResponse
    {
      $validatedWishlistSaveRequest = $wishlistSaveRequest->validated();
      $validatedWishlistSaveRequest['user_id'] = Auth::id();
      $validatedWishlistSaveRequest['slug'] = Str::slug($validatedWishlistSaveRequest['name']);
      $wishlistModel = new Wishlist($validatedWishlistSaveRequest);
      $saveState = $wishlistModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Wishlist created successfully"))
          ->getJsonResource())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     * @throws HttpResponseException
     */
    public function update(WishlistSaveRequest $wishlistSaveRequest, int $wishlistId): JsonResponse
    {
      $wishlistModel = Wishlist::query()->find($wishlistId)->where('user_id', Auth::id());
      $validatedWishlistSaveRequest = $wishlistSaveRequest->validated();
      $wishlistModel->fill($validatedWishlistSaveRequest);
      $saveState = $wishlistModel->save();
      $this->commonHelper->validateOperationState($saveState);
      return response()
        ->json((new WebResponsePayload("Wishlist updated successfully"))
          ->getJsonResource())->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     * @throws HttpResponseException
     */
    public function destroy(int $wishlistId): JsonResponse
    {
      $deleteState = Wishlist::query()->find($wishlistId)->where('user_id', Auth::id())->delete();
      $this->commonHelper->validateOperationState($deleteState);
      return response()
        ->json((new WebResponsePayload("Wishlist deleted successfully"))
          ->getJsonResource())->setStatusCode(200);
    }
  }
