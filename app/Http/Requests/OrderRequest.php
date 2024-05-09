<?php

  namespace App\Http\Requests;

  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;

  class OrderRequest extends FormRequest
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
        "address_id" => ["required"],
        "expedition_id" => ["required", "exists:expeditions,id"],
        "order_payload" => ["required", "array"],
        "order_payload.*.product_id" => ["required", "numeric", "exists:products,id"],
        "order_payload.*.quantity" => ["required", "numeric", "min:1"],
        "order_payload.*.note" => ["nullable", "string", "min:1"]
      ];
    }
  }
