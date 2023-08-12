<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use ReCaptcha\ReCaptcha;

class ReCaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptcha = new ReCaptcha(env('RE_CAPTCHA_SECRET_KEY'));
        $response = $recaptcha->setExpectedHostname(config('app.host'))
            ->verify($value);
        if ($response->isSuccess()) {
            return;
        }
        $fail('The reCAPTCHA verification failed. Please try again');
    }
}
