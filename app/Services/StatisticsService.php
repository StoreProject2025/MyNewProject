<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsService
{
    /**
     * Get user registration statistics
     *
     * @param int $days
     * @return array
     */
    public function getUserRegistrationStats(int $days = 30): array
    {
        return User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get active users count
     *
     * @param int $days
     * @return int
     */
    public function getActiveUsersCount(int $days = 30): int
    {
        return User::where('last_login_at', '>=', Carbon::now()->subDays($days))
            ->count();
    }

    /**
     * Get system storage usage
     *
     * @return array
     */
    public function getStorageUsage(): array
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total' => $totalSpace,
            'used' => $usedSpace,
            'free' => $freeSpace,
            'percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    /**
     * Get daily activity statistics
     *
     * @param string $model
     * @param int $days
     * @return array
     */
    public function getDailyActivityStats(string $model, int $days = 7): array
    {
        return $model::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Get monthly growth rate
     *
     * @param string $model
     * @return float
     */
    public function getMonthlyGrowthRate(string $model): float
    {
        $thisMonth = $model::whereMonth('created_at', Carbon::now()->month)->count();
        $lastMonth = $model::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();

        if ($lastMonth == 0) {
            return 100;
        }

        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
} 