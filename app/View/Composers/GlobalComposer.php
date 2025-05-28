<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Cache;

class GlobalComposer
{
    /**
     * Statistics service instance
     *
     * @var StatisticsService
     */
    protected $statsService;

    /**
     * Create a new profile composer.
     *
     * @param StatisticsService $statsService
     */
    public function __construct(StatisticsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'globalStats' => $this->getGlobalStats(),
            'notifications' => $this->getNotifications(),
            'siteSettings' => $this->getSiteSettings(),
            'menuItems' => $this->getMenuItems(),
        ]);
    }

    /**
     * Get global statistics
     *
     * @return array
     */
    protected function getGlobalStats(): array
    {
        return Cache::remember('global_stats', 3600, function () {
            return [
                'total_users' => \App\Models\User::count(),
                'active_users' => $this->statsService->getActiveUsersCount(),
                'storage_usage' => $this->statsService->getStorageUsage(),
            ];
        });
    }

    /**
     * Get global notifications
     *
     * @return array
     */
    protected function getNotifications(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return auth()->user()
            ->unreadNotifications()
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Get site settings
     *
     * @return array
     */
    protected function getSiteSettings(): array
    {
        return Cache::remember('site_settings', 86400, function () {
            return [
                'site_name' => config('app.name'),
                'theme' => 'light',
                'maintenance_mode' => app()->isDownForMaintenance(),
                'version' => config('app.version', '1.0.0'),
            ];
        });
    }

    /**
     * Get menu items
     *
     * @return array
     */
    protected function getMenuItems(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'url' => route('dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'label' => 'Users',
                'url' => route('users.index'),
                'icon' => 'users',
            ],
            [
                'label' => 'Settings',
                'url' => route('settings.index'),
                'icon' => 'settings',
            ],
        ];
    }
} 