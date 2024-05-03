<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaveRequest extends FormRequest
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
          'name' => 'required|string|max:60',
          'domain' => 'required|string|max:24',
          'slogan' => 'required|string|max:48',
          'location_name' => 'required|string|max:25',
          'city' => 'required|string|max:50',
          'zip_code' => 'required|string|max:10',
          'detail' => 'required|string|max:255',
          'description' => 'required|string|max:140',
        ];
    }
}
