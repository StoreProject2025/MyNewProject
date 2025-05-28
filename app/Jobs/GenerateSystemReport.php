<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Storage;

class GenerateSystemReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(StatisticsService $statsService)
    {
        $report = [
            'generated_at' => now()->toDateTimeString(),
            'system_stats' => [
                'storage' => $statsService->getStorageUsage(),
                'users' => [
                    'total' => \App\Models\User::count(),
                    'active' => $statsService->getActiveUsersCount(),
                    'growth_rate' => $statsService->getMonthlyGrowthRate(\App\Models\User::class)
                ],
                'activity' => [
                    'daily' => $statsService->getDailyActivityStats(\App\Models\User::class),
                    'registrations' => $statsService->getUserRegistrationStats()
                ]
            ]
        ];

        $this->saveReport($report);
        $this->notifyAdmins($report);
    }

    /**
     * Save the generated report
     *
     * @param array $report
     * @return void
     */
    protected function saveReport(array $report): void
    {
        $filename = 'reports/system/report-' . now()->format('Y-m-d') . '.json';
        Storage::put($filename, json_encode($report, JSON_PRETTY_PRINT));
    }

    /**
     * Notify administrators about the report
     *
     * @param array $report
     * @return void
     */
    protected function notifyAdmins(array $report): void
    {
        // You would implement the notification logic here
        // For example, sending emails to admin users
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        \Log::error('System report generation failed', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
} 