<?php

  namespace App\Http\Requests;

  use Illuminate\Contracts\Validation\ValidationRule;
  use Illuminate\Foundation\Http\FormRequest;

  class UserProfileUpdateRequest extends FormRequest
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
        "first_name" => ["required", "string", "max:255"],
        "last_name" => ["required", "string", "max:255"],
        "email" => ["required", "email", "max:255", "unique:user_profiles,email"],
        "phone" => ["required", "numeric", "max:255"],
        "birth_date" => ["required", "date", "date_format:Y-m-d"],
        "gender" => ["required", "string", Rule::enum(UserGender::class)],
        "image_path" => ["nullable", "image", "mimes:jpeg,png,jpg,gif,svg", "max:5120"],
      ];
    }
  }
