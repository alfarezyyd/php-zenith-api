<?php

  namespace App\Http\Requests;

  use App\Enums\ProductCondition;
  use App\Enums\ProductStatus;
  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;
  use Illuminate\Validation\Rule;

  class ProductSaveRequest extends FormRequest
  {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
      return [
        'name' => 'required|string|max:50',
        'condition' => ['required', 'string', Rule::enum(ProductCondition::class)],
        'description' => 'required|string|min:10',
        'price' => 'required|numeric|min:1',
        'minimum_order' => 'required|numeric|min:1',
        'status' => ['required', 'string', Rule::enum(ProductStatus::class)],
        'stock' => 'required|numeric|min:1',
        'sku' => 'required|string|max:50',
        'weight' => 'required|numeric|min:1',
        'width' => 'required|numeric|min:1',
        'height' => 'required|numeric|min:1',
        'category_ids' => 'required|array|min:1',
        'category_ids.*' => 'integer|min:1|exists:categories,id',
        'images' => 'array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
      ];
    }
  }
