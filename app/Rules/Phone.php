<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Phone implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected $country = 'EG',
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->passes($attribute, $value)) {
            return;
        }

        $fail('validation.phone')->translate();
    }

    /**
     * Check if the validation rule passes.
     */
    public function passes(string $attribute, mixed $value): bool
    {
        $instance = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $phone = $instance->parse($value, $this->country);

            return $instance->isValidNumber($phone);
        } catch (\libphonenumber\NumberParseException) {
            return false;
        }
    }
}
