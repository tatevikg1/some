<?php

namespace App\Http\Requests\Login;


use Illuminate\Foundation\Http\FormRequest;

class Enable2FaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'twoFactorCode' => 'required|string'
        ];
    }
}
