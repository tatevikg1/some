<?php

namespace App\Http\Requests\Login;

use App\Models\SocialProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialRedirectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|string|' . Rule::in(array_values(SocialProfile::REDIRECT_MAP)),
        ];
    }
}
