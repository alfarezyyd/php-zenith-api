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
    public function store(): string
    {
      $loggedUserId = Auth::id();
      $newCart = new Cart(['user_id' => $loggedUserId]);
      $saveState = $newCart->save();
      $this->commonHelper->validateOperationState($saveState);
      return 'Cart created successfully';
    }

    public function attachProduct(ProductCartAttachRequest $productCartSaveRequest): string
    {
      $validatedProductCartSaveRequest = $productCartSaveRequest->validated();
      $productModel = Product::query()->findOrFail($validatedProductCartSaveRequest['product_id']);
      $cartModel = Cart::query()->findOrFail($validatedProductCartSaveRequest['cart_id']);
      $validatedProductCartSaveRequest['sub_total_price'] = $productModel['price'] * $cartModel['quantity'];
      $productCartModel = new ProductCart($validatedProductCartSaveRequest);
      $productCartModel->save();
      return "Attach product successfully";
    }

    public function detachProduct(int $productCartId): string
    {
      ProductCart::query()->where('id', $productCartId)->first()->delete();
      return "Detach product successfully";
    }
  }
