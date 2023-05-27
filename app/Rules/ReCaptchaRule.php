<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ReCaptcha\ReCaptcha;

class ReCaptchaRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $recaptcha = new ReCaptcha(env('RE_CAPTCHA_SECRET_KEY'));
        $response = $recaptcha->setExpectedHostname(config('app.host'))
            ->verify($value);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }

    public function message(): string
    {
        return 'The reCAPTCHA verification failed. Please try again.';
    }
}
