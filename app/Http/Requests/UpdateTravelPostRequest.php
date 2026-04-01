<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelPostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            "title" => ["sometimes", "min:1", "max:300"],
            "location" => ["sometimes", "min:1", "max:100"],
            "country" => ["sometimes", "min:1", "max:100"],
            "description" => ["sometimes", "min:1", "max:500"],
            'img' => ['nullable', 'image', 'max:2048'], //sometimes non c'è proprio nella richiesta, nullable gli passo null
        ];
    }
}
