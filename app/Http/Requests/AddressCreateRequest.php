<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class AddressCreateRequest extends FormRequest
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
                "label" => ["required", "string", "max:30"],
                "street" => ["required", "string", "max:255"],
                "neighbourhood_number" => ["required", "string", "max:10"],
                "hamlet_number" => ["required", "string", "max:10"],
                "village" => ["required", "string", "max:10"],
                "urban_village" => ["required", "string", "max:255"],
                "sub_district" => ["required", "string", "max:255"],
                "expedition_city_id" => ["required", "integer", "gt:0"],
                "expedition_province_id" => ["required", "integer", "gt:0"],
                "postal_code" => ["required", "string", "max:10"],
                "note" => ["required", "string", "max:50"],
                "receiver_name" => ["required", "string", "max:50"],
                "telephone" => ["required", "string", "max:15"],
            ];
        }

        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response([
                "errors" => $validator->getMessageBag()
            ], 400));
        }
    }
