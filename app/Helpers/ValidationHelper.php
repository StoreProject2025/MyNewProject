<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Get common validation rules
     *
     * @return array
     */
    public static function getCommonRules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'url' => ['required', 'url'],
            'date' => ['required', 'date'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'file' => ['required', 'file', 'max:10240'],
        ];
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public static function getMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'The :attribute must be a valid email address.',
            'min' => [
                'string' => 'The :attribute must be at least :min characters.',
                'file' => 'The :attribute must be at least :min kilobytes.',
                'numeric' => 'The :attribute must be at least :min.',
                'array' => 'The :attribute must have at least :min items.',
            ],
            'max' => [
                'string' => 'The :attribute may not be greater than :max characters.',
                'file' => 'The :attribute may not be greater than :max kilobytes.',
                'numeric' => 'The :attribute may not be greater than :max.',
                'array' => 'The :attribute may not have more than :max items.',
            ],
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'url' => 'The :attribute format is invalid.',
            'date' => 'The :attribute is not a valid date.',
            'regex' => 'The :attribute format is invalid.',
        ];
    }

    /**
     * Get password validation rules
     *
     * @param bool $requireSpecialChar
     * @param bool $requireNumber
     * @param int $minLength
     * @return array
     */
    public static function getPasswordRules(bool $requireSpecialChar = true, bool $requireNumber = true, int $minLength = 8): array
    {
        $rules = ['required', "min:{$minLength}"];

        if ($requireSpecialChar) {
            $rules[] = 'regex:/[!@#$%^&*(),.?":{}|<>]/';
        }

        if ($requireNumber) {
            $rules[] = 'regex:/[0-9]/';
        }

        return $rules;
    }

    /**
     * Get file validation rules
     *
     * @param array $mimes
     * @param int $maxSize
     * @return array
     */
    public static function getFileRules(array $mimes = [], int $maxSize = 2048): array
    {
        $rules = ['required', 'file', "max:{$maxSize}"];

        if (!empty($mimes)) {
            $rules[] = 'mimes:' . implode(',', $mimes);
        }

        return $rules;
    }

    /**
     * Get date validation rules
     *
     * @param string $format
     * @param string|null $after
     * @param string|null $before
     * @return array
     */
    public static function getDateRules(string $format = 'Y-m-d', ?string $after = null, ?string $before = null): array
    {
        $rules = ['required', 'date', "date_format:{$format}"];

        if ($after) {
            $rules[] = "after:{$after}";
        }

        if ($before) {
            $rules[] = "before:{$before}";
        }

        return $rules;
    }

    /**
     * Get numeric validation rules
     *
     * @param float|null $min
     * @param float|null $max
     * @param bool $integer
     * @return array
     */
    public static function getNumericRules(?float $min = null, ?float $max = null, bool $integer = false): array
    {
        $rules = ['required', $integer ? 'integer' : 'numeric'];

        if ($min !== null) {
            $rules[] = "min:{$min}";
        }

        if ($max !== null) {
            $rules[] = "max:{$max}";
        }

        return $rules;
    }
} 