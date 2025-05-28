<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogService
{
    /**
     * Log an activity
     *
     * @param string $action
     * @param Model|null $model
     * @param array $data
     * @return void
     */
    public function log(string $action, ?Model $model = null, array $data = []): void
    {
        try {
            $logData = [
                'action' => $action,
                'user_id' => Auth::id(),
                'user_type' => Auth::user()?->type ?? 'system',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
                'data' => $data
            ];

            if ($model) {
                $logData['model_type'] = get_class($model);
                $logData['model_id'] = $model->getKey();
            }

            // Store in database
            $this->storeLog($logData);

            // Also log to file for critical actions
            if ($this->isCriticalAction($action)) {
                Log::channel('activity')->info('Critical activity', $logData);
            }
        } catch (\Exception $e) {
            Log::error('Failed to log activity', [
                'error' => $e->getMessage(),
                'action' => $action,
                'data' => $data
            ]);
        }
    }

    /**
     * Log a system activity
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    public function logSystem(string $action, array $data = []): void
    {
        $this->log($action, null, array_merge($data, [
            'type' => 'system'
        ]));
    }

    /**
     * Log an error
     *
     * @param string $action
     * @param \Throwable $exception
     * @param array $context
     * @return void
     */
    public function logError(string $action, \Throwable $exception, array $context = []): void
    {
        $this->log($action, null, [
            'type' => 'error',
            'error_message' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'error_trace' => $exception->getTraceAsString(),
            'context' => $context
        ]);
    }

    /**
     * Get activities for a specific model
     *
     * @param Model $model
     * @param int $limit
     * @return array
     */
    public function getModelActivities(Model $model, int $limit = 10): array
    {
        return \DB::table('activity_logs')
            ->where('model_type', get_class($model))
            ->where('model_id', $model->getKey())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get user activities
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserActivities(int $userId, int $limit = 10): array
    {
        return \DB::table('activity_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Store activity log in database
     *
     * @param array $data
     * @return void
     */
    protected function storeLog(array $data): void
    {
        \DB::table('activity_logs')->insert($data);
    }

    /**
     * Check if action is critical
     *
     * @param string $action
     * @return bool
     */
    protected function isCriticalAction(string $action): bool
    {
        $criticalActions = [
            'user.delete',
            'role.update',
            'permission.update',
            'settings.update',
            'security.breach',
            'backup.create',
            'backup.restore',
            'system.maintenance'
        ];

        return in_array($action, $criticalActions);
    }

    /**
     * Clean old logs
     *
     * @param int $days
     * @return int
     */
    public function cleanOldLogs(int $days = 30): int
    {
        return \DB::table('activity_logs')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
} 