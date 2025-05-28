<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Minimum password length
     *
     * @var int
     */
    protected $minLength = 8;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < $this->minLength) {
            $fail("The {$attribute} must be at least {$this->minLength} characters.");
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail("The {$attribute} must contain at least one uppercase letter.");
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail("The {$attribute} must contain at least one lowercase letter.");
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail("The {$attribute} must contain at least one number.");
            return;
        }

        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail("The {$attribute} must contain at least one special character.");
            return;
        }

        // Check for common passwords
        if ($this->isCommonPassword($value)) {
            $fail("The {$attribute} is too common. Please choose a more secure password.");
            return;
        }
    }

    /**
     * Check if the password is in the list of common passwords
     *
     * @param string $password
     * @return bool
     */
    protected function isCommonPassword(string $password): bool
    {
        $commonPasswords = [
            'password',
            'password123',
            '123456',
            '12345678',
            'qwerty',
            'abc123',
            'admin123',
            'welcome',
            'welcome123',
            'letmein',
        ];

        return in_array(strtolower($password), $commonPasswords);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a strong password.';
    }
} 