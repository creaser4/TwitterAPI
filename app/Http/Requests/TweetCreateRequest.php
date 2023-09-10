<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TweetCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'text' => 'required|max:280',
            'media' => 'nullable|file|max:2048', // Example: Allowing file uploads up to 2MB
        ];

        // Add a conditional rule for editing tweets
        if ($this->isMethod('patch')) {
            // You can add rules specific to editing here
            $rules['text'] = 'required|max:280'; // Example: Text is required for editing
        }

        return $rules;
    }
}
