<?php

  namespace App\Http\Requests;

  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;

  class CategoryCreateRequest extends FormRequest
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
        'name' => 'required|string|unique:categories,name|max:100',
        'category_id' => 'integer|gt:0'
      ];
    }
  }
