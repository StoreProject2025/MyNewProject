<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptionHelper
{
    /**
     * Generate a random encryption key
     *
     * @param int $length
     * @return string
     */
    public static function generateKey(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Encrypt a value
     *
     * @param mixed $value
     * @return string
     */
    public static function encrypt($value): string
    {
        return Crypt::encrypt($value);
    }

    /**
     * Decrypt a value
     *
     * @param string $encrypted
     * @return mixed
     */
    public static function decrypt(string $encrypted)
    {
        try {
            return Crypt::decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Hash a password
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Check if a password matches its hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function checkPassword(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }

    /**
     * Generate a random token
     *
     * @param int $length
     * @return string
     */
    public static function generateToken(int $length = 64): string
    {
        return Str::random($length);
    }

    /**
     * Generate a UUID
     *
     * @return string
     */
    public static function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Generate a secure hash of a value
     *
     * @param mixed $value
     * @param string $algorithm
     * @return string
     */
    public static function hash($value, string $algorithm = 'sha256'): string
    {
        return hash($algorithm, serialize($value));
    }

    /**
     * Generate a HMAC hash
     *
     * @param mixed $value
     * @param string $key
     * @param string $algorithm
     * @return string
     */
    public static function hmac($value, string $key, string $algorithm = 'sha256'): string
    {
        return hash_hmac($algorithm, serialize($value), $key);
    }

    /**
     * Encode data to base64
     *
     * @param mixed $data
     * @return string
     */
    public static function base64Encode($data): string
    {
        return base64_encode(serialize($data));
    }

    /**
     * Decode base64 data
     *
     * @param string $encoded
     * @return mixed
     */
    public static function base64Decode(string $encoded)
    {
        try {
            return unserialize(base64_decode($encoded));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate a random secure password
     *
     * @param int $length
     * @param bool $includeSpecialChars
     * @return string
     */
    public static function generatePassword(int $length = 12, bool $includeSpecialChars = true): string
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
     * Check if a string is encrypted
     *
     * @param string $value
     * @return bool
     */
    public static function isEncrypted(string $value): bool
    {
        try {
            Crypt::decrypt($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate a secure API key
     *
     * @param string $prefix
     * @return string
     */
    public static function generateApiKey(string $prefix = ''): string
    {
        $random = bin2hex(random_bytes(16));
        $timestamp = time();
        $hash = hash('sha256', $random . $timestamp);
        
        return $prefix ? $prefix . '_' . $hash : $hash;
    }
} 