<?php

  namespace App\Http\Requests;

  use Illuminate\Contracts\Validation\Validator;
  use Illuminate\Foundation\Http\FormRequest;
  use Illuminate\Http\Exceptions\HttpResponseException;
  use Illuminate\Validation\ValidationException;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        'name' => 'required|string|unique:categories,name|max:255',
        'category_id' => 'integer|gt:0'
      ];
    }
  }
