<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProcessImageUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $retryAfter = 30;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    protected $imagePath;
    protected $targetPath;
    protected $options;

    /**
     * Create a new job instance.
     */
    public function __construct(string $imagePath, string $targetPath, array $options = [])
    {
        $this->imagePath = $imagePath;
        $this->targetPath = $targetPath;
        $this->options = array_merge([
            'resize_width' => null,
            'resize_height' => null,
            'quality' => 85,
            'create_thumbnail' => true,
            'thumbnail_width' => 300,
            'thumbnail_height' => 200,
            'optimize' => true,
        ], $options);

        $this->onQueue('images');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Processing image upload job started', [
                'source_path' => $this->imagePath,
                'target_path' => $this->targetPath,
                'job_id' => $this->job->getJobId(),
            ]);

            // Check if source file exists
            if (!Storage::disk('public')->exists($this->imagePath)) {
                throw new \Exception("Source image not found: {$this->imagePath}");
            }

            // Process main image
            $this->processMainImage();

            // Process thumbnail if requested
            if ($this->options['create_thumbnail']) {
                $this->processThumbnail();
            }

            // Clean up temporary files
            $this->cleanup();

            Log::info('Image processing completed successfully', [
                'target_path' => $this->targetPath,
                'job_id' => $this->job->getJobId(),
            ]);
        } catch (\Exception $e) {
            Log::error('Image processing failed', [
                'source_path' => $this->imagePath,
                'target_path' => $this->targetPath,
                'error' => $e->getMessage(),
                'job_id' => $this->job->getJobId(),
                'attempt' => $this->attempts(),
            ]);

            throw $e;
        }
    }

    /**
     * Process the main image.
     */
    protected function processMainImage(): void
    {
        $image = Image::make(Storage::disk('public')->path($this->imagePath));

        // Resize if dimensions are specified
        if ($this->options['resize_width'] || $this->options['resize_height']) {
            $image->resize(
                $this->options['resize_width'],
                $this->options['resize_height'],
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
        }

        // Optimize if requested
        if ($this->options['optimize']) {
            $image->encode('jpg', $this->options['quality']);
        }

        // Save the processed image
        Storage::disk('public')->put($this->targetPath, $image->stream());
    }

    /**
     * Process thumbnail image.
     */
    protected function processThumbnail(): void
    {
        $thumbnailPath = $this->getThumbnailPath();
        
        $image = Image::make(Storage::disk('public')->path($this->imagePath));
        
        $image->fit(
            $this->options['thumbnail_width'],
            $this->options['thumbnail_height'],
            function ($constraint) {
                $constraint->upsize();
            }
        );

        Storage::disk('public')->put($thumbnailPath, $image->stream());
    }

    /**
     * Get thumbnail path.
     */
    protected function getThumbnailPath(): string
    {
        $pathInfo = pathinfo($this->targetPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
    }

    /**
     * Clean up temporary files.
     */
    protected function cleanup(): void
    {
        // Remove original uploaded file if it's different from target
        if ($this->imagePath !== $this->targetPath) {
            Storage::disk('public')->delete($this->imagePath);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Image processing job failed permanently', [
            'source_path' => $this->imagePath,
            'target_path' => $this->targetPath,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
            'job_id' => $this->job->getJobId(),
        ]);

        // Clean up on failure
        $this->cleanup();
    }
}
