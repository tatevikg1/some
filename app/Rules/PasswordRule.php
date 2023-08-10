<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 5) {
            $fail("$attribute is too short, $attribute should be at least 6 char");
        }

        // Password should contain at least one numeric digit
        if (!preg_match('/\d/', $value)) {
            $fail("$attribute should contain at lease 1 numeric value");
        }

        // Password should contain at least one symbol
        if (!preg_match('/[^a-zA-Z\d]/', $value)) {
            $fail("$attribute should have at least one symbol");
        }
    }
}
