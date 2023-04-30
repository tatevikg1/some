<?php


namespace App\Http\Requests\Login;


use App\Models\SocialProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'network' => 'required|string|' . Rule::in(array_values(SocialProfile::DRIVER_MAP)),
            'token' => 'required|string',
        ];
    }
}
