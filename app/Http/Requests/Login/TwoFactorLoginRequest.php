<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'twoFactorSecret' => 'string',
            'twoFactorIdentifier' => 'string'
        ];
    }
}
