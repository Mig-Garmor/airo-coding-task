<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidAgeList implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a comma-separated list of ages.');

            return;
        }

        $parts = explode(',', $value);

        foreach ($parts as $part) {
            $age = trim($part);

            if ($age === '' || ! ctype_digit($age)) {
                $fail('The :attribute must be a comma-separated list of valid ages.');

                return;
            }

            $ageAsInteger = (int) $age;

            if ($ageAsInteger < 18 || $ageAsInteger > 70) {
                $fail('Each age must be between 18 and 70.');

                return;
            }
        }
    }
}
