<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class OptimizeImage implements ShouldQueue
{
    use Queueable;

    public $filePath;
    public $maxWidth;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param int $maxWidth
     */
    public function __construct(string $filePath, int $maxWidth = 1200)
    {
        $this->filePath = $filePath;
        $this->maxWidth = $maxWidth;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $disk = Storage::disk('public');
        
        if (!$disk->exists($this->filePath)) {
            return;
        }

        $absolutePath = $disk->path($this->filePath);

        // Initialize Intervention Image with GD driver
        $manager = new ImageManager(new Driver());
        
        try {
            $image = $manager->read($absolutePath);
            
            // Resize image if it exceeds the maximum width while preserving aspect ratio
            if ($image->width() > $this->maxWidth) {
                $image->scaleDown(width: $this->maxWidth);
            }
            
            // Overwrite the same file to keep the URL intact, but compress it
            $image->save($absolutePath, quality: 75);
            
        } catch (\Exception $e) {
            // Log error or let it fail
            \Log::error('Image optimization failed: ' . $e->getMessage());
        }
    }
}
