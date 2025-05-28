<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupService
{
    /**
     * Backup directory
     *
     * @var string
     */
    protected $backupPath = 'backups';

    /**
     * Create a new backup
     *
     * @return array
     */
    public function create(): array
    {
        try {
            $filename = $this->generateBackupFilename();
            
            // Backup database
            $this->backupDatabase($filename);
            
            // Backup files
            $this->backupFiles($filename);
            
            // Create backup record
            $backup = $this->createBackupRecord($filename);
            
            // Clean old backups
            $this->cleanOldBackups();
            
            return [
                'success' => true,
                'backup' => $backup
            ];
        } catch (\Exception $e) {
            Log::error('Backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Restore from backup
     *
     * @param string $filename
     * @return array
     */
    public function restore(string $filename): array
    {
        try {
            // Verify backup exists
            if (!$this->backupExists($filename)) {
                throw new \Exception('Backup file not found');
            }

            // Stop application
            Artisan::call('down');

            // Restore database
            $this->restoreDatabase($filename);

            // Restore files
            $this->restoreFiles($filename);

            // Start application
            Artisan::call('up');

            return [
                'success' => true,
                'message' => 'Backup restored successfully'
            ];
        } catch (\Exception $e) {
            // Ensure application is up
            Artisan::call('up');

            Log::error('Restore failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all backups
     *
     * @return array
     */
    public function getAllBackups(): array
    {
        return \DB::table('backups')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($backup) {
                return [
                    'id' => $backup->id,
                    'filename' => $backup->filename,
                    'size' => $this->getBackupSize($backup->filename),
                    'created_at' => Carbon::parse($backup->created_at)->format('Y-m-d H:i:s')
                ];
            })
            ->toArray();
    }

    /**
     * Delete a backup
     *
     * @param string $filename
     * @return bool
     */
    public function deleteBackup(string $filename): bool
    {
        try {
            // Delete files
            Storage::disk('backups')->delete($filename);
            Storage::disk('backups')->delete($filename . '.zip');

            // Delete record
            \DB::table('backups')->where('filename', $filename)->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Delete backup failed', [
                'error' => $e->getMessage(),
                'filename' => $filename
            ]);

            return false;
        }
    }

    /**
     * Generate backup filename
     *
     * @return string
     */
    protected function generateBackupFilename(): string
    {
        return 'backup_' . now()->format('Y_m_d_His');
    }

    /**
     * Backup database
     *
     * @param string $filename
     * @return void
     */
    protected function backupDatabase(string $filename): void
    {
        Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $filename . '.sql'
        ]);
    }

    /**
     * Backup files
     *
     * @param string $filename
     * @return void
     */
    protected function backupFiles(string $filename): void
    {
        $zip = new ZipArchive();
        $zip->open(storage_path("app/backups/{$filename}.zip"), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(base_path()),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(base_path()) + 1);

                if ($this->shouldBackupFile($relativePath)) {
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }

        $zip->close();
    }

    /**
     * Check if file should be backed up
     *
     * @param string $path
     * @return bool
     */
    protected function shouldBackupFile(string $path): bool
    {
        $excludedPaths = [
            'vendor/',
            'node_modules/',
            'storage/logs/',
            'storage/framework/cache/',
            '.git/',
            '.env'
        ];

        foreach ($excludedPaths as $excludedPath) {
            if (str_starts_with($path, $excludedPath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create backup record
     *
     * @param string $filename
     * @return array
     */
    protected function createBackupRecord(string $filename): array
    {
        return \DB::table('backups')->insert([
            'filename' => $filename,
            'created_at' => now(),
            'created_by' => auth()->id() ?? 1
        ]);
    }

    /**
     * Clean old backups
     *
     * @param int $days
     * @return void
     */
    protected function cleanOldBackups(int $days = 7): void
    {
        $oldBackups = \DB::table('backups')
            ->where('created_at', '<', now()->subDays($days))
            ->get();

        foreach ($oldBackups as $backup) {
            $this->deleteBackup($backup->filename);
        }
    }

    /**
     * Get backup size
     *
     * @param string $filename
     * @return string
     */
    protected function getBackupSize(string $filename): string
    {
        $bytes = Storage::disk('backups')->size($filename . '.zip');
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
    }

    /**
     * Check if backup exists
     *
     * @param string $filename
     * @return bool
     */
    protected function backupExists(string $filename): bool
    {
        return Storage::disk('backups')->exists($filename . '.zip') &&
               Storage::disk('backups')->exists($filename . '.sql');
    }
} 