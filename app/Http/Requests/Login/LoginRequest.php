<?php


namespace App\Http\Requests\Login;


use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required_without:username|email',
            'username' => 'required_without:email|string|min:3|max:100',
            'password' => 'required|string',
            'rememberMe' => 'sometimes|required|boolean',
            'scopes' => 'sometimes|required|array',
            'scopes.*' => 'required_with:scopes|string'
        ];
    }
}
