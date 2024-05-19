<?php

  namespace App\Services;

  use App\Helpers\CommonHelper;
  use App\Http\Requests\ProductCartAttachRequest;
  use App\Models\Cart;
  use App\Models\Product;
  use App\Models\ProductCart;
  use HttpResponseException;
  use Illuminate\Support\Facades\Auth;

  class CartService
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
     * @throws HttpResponseException
     */
    public function store(int $userId): string
    {
      $newCart = new Cart(['user_id' => $userId]);
      $saveState = $newCart->save();
      $this->commonHelper->validateOperationState($saveState);
      return 'Cart created successfully';
    }
  }
