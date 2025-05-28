<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    /**
     * Cache key for settings
     */
    const CACHE_KEY = 'app_settings';

    /**
     * Cache duration in seconds (24 hours)
     */
    const CACHE_DURATION = 86400;

    /**
     * Get all settings
     *
     * @return array
     */
    public function getAllSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return $this->loadSettings();
        });
    }

    /**
     * Get setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAllSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        // Implementation would depend on your settings storage method
        // This is just a placeholder
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        $this->clearCache();
    }

    /**
     * Set multiple settings
     *
     * @param array $settings
     * @return void
     */
    public function setMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Delete setting
     *
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        // Implementation would depend on your settings storage method
        // This is just a placeholder
        DB::table('settings')->where('key', $key)->delete();
        
        $this->clearCache();
    }

    /**
     * Clear settings cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get default settings
     *
     * @return array
     */
    public function getDefaultSettings(): array
    {
        return [
            'site_name' => 'My Application',
            'site_description' => 'A Laravel Application',
            'mail_from_address' => 'noreply@example.com',
            'mail_from_name' => 'My Application',
            'pagination_limit' => 15,
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'notification_email' => 'admin@example.com',
            'enable_registration' => true,
            'maintenance_mode' => false,
            'theme' => 'light',
            'social_links' => [
                'facebook' => '',
                'twitter' => '',
                'linkedin' => ''
            ]
        ];
    }

    /**
     * Load settings from storage
     *
     * @return array
     */
    protected function loadSettings(): array
    {
        // Implementation would depend on your settings storage method
        // This is just a placeholder
        $settings = DB::table('settings')->get();
        
        $result = [];
        foreach ($settings as $setting) {
            $value = $setting->value;
            // Try to decode JSON values
            if (is_string($value) && $this->isJson($value)) {
                $value = json_decode($value, true);
            }
            $result[$setting->key] = $value;
        }

        return array_merge($this->getDefaultSettings(), $result);
    }

    /**
     * Check if string is valid JSON
     *
     * @param string $string
     * @return bool
     */
    protected function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
} 