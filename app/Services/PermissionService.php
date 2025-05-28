<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    /**
     * Cache duration in seconds
     */
    const CACHE_DURATION = 3600;

    /**
     * Get all available permissions
     *
     * @return array
     */
    public function getAllPermissions(): array
    {
        return [
            'users' => [
                'view',
                'create',
                'edit',
                'delete',
                'manage-roles'
            ],
            'posts' => [
                'view',
                'create',
                'edit',
                'delete',
                'publish'
            ],
            'settings' => [
                'view',
                'edit'
            ],
            'reports' => [
                'view',
                'generate',
                'export'
            ]
        ];
    }

    /**
     * Check if user has permission
     *
     * @param User $user
     * @param string $permission
     * @return bool
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return Cache::remember(
            "user_permissions_{$user->id}_{$permission}",
            self::CACHE_DURATION,
            fn() => $this->checkPermission($user, $permission)
        );
    }

    /**
     * Get user permissions
     *
     * @param User $user
     * @return Collection
     */
    public function getUserPermissions(User $user): Collection
    {
        return Cache::remember(
            "user_permissions_{$user->id}",
            self::CACHE_DURATION,
            fn() => $this->loadUserPermissions($user)
        );
    }

    /**
     * Sync user permissions
     *
     * @param User $user
     * @param array $permissions
     * @return void
     */
    public function syncPermissions(User $user, array $permissions): void
    {
        // Implementation would depend on your permission storage method
        // This is just a placeholder
        $user->permissions()->sync($permissions);
        
        // Clear user permissions cache
        $this->clearPermissionCache($user);
    }

    /**
     * Clear user permissions cache
     *
     * @param User $user
     * @return void
     */
    public function clearPermissionCache(User $user): void
    {
        Cache::forget("user_permissions_{$user->id}");
        
        foreach ($this->getAllPermissions() as $group => $permissions) {
            foreach ($permissions as $permission) {
                Cache::forget("user_permissions_{$user->id}_{$group}.{$permission}");
            }
        }
    }

    /**
     * Check specific permission
     *
     * @param User $user
     * @param string $permission
     * @return bool
     */
    protected function checkPermission(User $user, string $permission): bool
    {
        // Super admin has all permissions
        if ($user->is_super_admin) {
            return true;
        }

        return $user->permissions->contains('name', $permission);
    }

    /**
     * Load user permissions from database
     *
     * @param User $user
     * @return Collection
     */
    protected function loadUserPermissions(User $user): Collection
    {
        // Implementation would depend on your permission storage method
        // This is just a placeholder
        return $user->permissions;
    }
} 