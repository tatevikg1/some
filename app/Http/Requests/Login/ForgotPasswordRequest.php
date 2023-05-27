<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users'
            ],
//            'captcha' => 'sometimes|required|boolean',
//            'captchaResponse' => [
//                'nullable',
//                'required_if:captcha,1',
//                'string'
//            ],
        ];
    }

}
