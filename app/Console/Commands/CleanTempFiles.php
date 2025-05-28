<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:temp-files {--days=7 : Number of days to keep files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean temporary files older than specified days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        $this->info("Cleaning temporary files older than {$days} days...");

        $disk = Storage::disk('temp');
        $files = $disk->allFiles();
        $deletedCount = 0;

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));
            $daysOld = $lastModified->diffInDays(now());

            if ($daysOld >= $days) {
                try {
                    $disk->delete($file);
                    $deletedCount++;
                    $this->line("Deleted: {$file}");
                } catch (\Exception $e) {
                    $this->error("Failed to delete {$file}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Cleaning completed. Deleted {$deletedCount} files.");
        
        return Command::SUCCESS;
    }

    /**
     * Get the temp storage disk
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getTempDisk()
    {
        return Storage::disk('temp');
    }

    /**
     * Check if file is older than specified days
     *
     * @param string $file
     * @param int $days
     * @return bool
     */
    protected function isFileOld($file, $days)
    {
        $disk = $this->getTempDisk();
        $lastModified = Carbon::createFromTimestamp($disk->lastModified($file));
        return $lastModified->diffInDays(now()) >= $days;
    }
} 