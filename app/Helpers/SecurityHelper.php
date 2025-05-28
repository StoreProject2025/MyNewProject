<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SecurityHelper
{
    /**
     * Generate a secure random token
     *
     * @param int $length
     * @return string
     */
    public static function generateToken(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Hash a password securely
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Verify password hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }

    /**
     * Generate a secure API key
     *
     * @param int $length
     * @return string
     */
    public static function generateApiKey(int $length = 40): string
    {
        return base64_encode(random_bytes($length));
    }

    /**
     * Sanitize input string
     *
     * @param string $input
     * @return string
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Generate CSRF token
     *
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        if (function_exists('csrf_token')) {
            return csrf_token();
        }
        return self::generateToken();
    }

    /**
     * Mask sensitive data
     *
     * @param string $data
     * @param int $visibleStart
     * @param int $visibleEnd
     * @return string
     */
    public static function maskData(string $data, int $visibleStart = 4, int $visibleEnd = 4): string
    {
        $length = strlen($data);
        if ($length <= ($visibleStart + $visibleEnd)) {
            return str_repeat('*', $length);
        }

        $middle = str_repeat('*', $length - ($visibleStart + $visibleEnd));
        return substr($data, 0, $visibleStart) . $middle . substr($data, -$visibleEnd);
    }

    /**
     * Check if string contains SQL injection attempts
     *
     * @param string $string
     * @return bool
     */
    public static function hasSqlInjection(string $string): bool
    {
        $sqlPatterns = [
            "\\bSELECT\\b",
            "\\bINSERT\\b",
            "\\bUPDATE\\b",
            "\\bDELETE\\b",
            "\\bDROP\\b",
            "\\bUNION\\b",
            "--",
            ";",
            "/*",
            "*/"
        ];

        return (bool) preg_match('/' . implode('|', $sqlPatterns) . '/i', $string);
    }

    /**
     * Generate a secure random password
     *
     * @param int $length
     * @param bool $includeSpecialChars
     * @return string
     */
    public static function generateSecurePassword(int $length = 12, bool $includeSpecialChars = true): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        if ($includeSpecialChars) {
            $chars .= $specialChars;
        }

        $password = '';
        $charsLength = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charsLength - 1)];
        }

        return $password;
    }

    /**
     * Validate password strength
     *
     * @param string $password
     * @return array
     */
    public static function validatePasswordStrength(string $password): array
    {
        $strength = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $strength++;
        } else {
            $feedback[] = 'Password should be at least 8 characters long';
        }

        if (preg_match('/[A-Z]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should include at least one uppercase letter';
        }

        if (preg_match('/[a-z]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should include at least one lowercase letter';
        }

        if (preg_match('/[0-9]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should include at least one number';
        }

        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $strength++;
        } else {
            $feedback[] = 'Password should include at least one special character';
        }

        return [
            'strength' => $strength,
            'feedback' => $feedback,
            'is_strong' => $strength >= 4
        ];
    }
} 