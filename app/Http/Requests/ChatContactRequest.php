<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'offset' => 'required|integer|min:0',
            'limit' => 'integer|min:2|max:20'
        ];
    }
}
