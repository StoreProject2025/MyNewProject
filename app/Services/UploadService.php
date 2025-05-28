<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UploadService
{
    /**
     * Allowed image extensions
     *
     * @var array
     */
    protected $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Allowed document extensions
     *
     * @var array
     */
    protected $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];

    /**
     * Upload an image with optional resizing
     *
     * @param UploadedFile $file
     * @param string $path
     * @param array $sizes
     * @return array
     */
    public function uploadImage(UploadedFile $file, string $path = 'images', array $sizes = []): array
    {
        $this->validateFile($file, $this->allowedImageExtensions);

        $filename = $this->generateFilename($file);
        $originalPath = $file->storeAs($path, $filename, 'public');
        
        $result = [
            'original' => $originalPath
        ];

        if (!empty($sizes)) {
            $result['sizes'] = $this->createImageSizes($file, $path, $filename, $sizes);
        }

        return $result;
    }

    /**
     * Upload a document
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public function uploadDocument(UploadedFile $file, string $path = 'documents'): string
    {
        $this->validateFile($file, $this->allowedDocumentExtensions);

        $filename = $this->generateFilename($file);
        return $file->storeAs($path, $filename, 'public');
    }

    /**
     * Delete uploaded file and its variations
     *
     * @param string $path
     * @param array $sizes
     * @return bool
     */
    public function deleteFile(string $path, array $sizes = []): bool
    {
        $deleted = Storage::disk('public')->delete($path);

        if ($deleted && !empty($sizes)) {
            foreach ($sizes as $size) {
                $sizePath = $this->getResizedImagePath($path, $size);
                Storage::disk('public')->delete($sizePath);
            }
        }

        return $deleted;
    }

    /**
     * Create different sizes of an image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $filename
     * @param array $sizes
     * @return array
     */
    protected function createImageSizes(UploadedFile $file, string $path, string $filename, array $sizes): array
    {
        $result = [];
        $image = Image::make($file);

        foreach ($sizes as $size => $dimensions) {
            $width = $dimensions[0] ?? null;
            $height = $dimensions[1] ?? null;

            $resized = $image->fit($width, $height);
            
            $sizePath = $this->getResizedImagePath("$path/$filename", $size);
            Storage::disk('public')->put($sizePath, $resized->encode());
            
            $result[$size] = $sizePath;
        }

        return $result;
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::random(40) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Get resized image path
     *
     * @param string $originalPath
     * @param string $size
     * @return string
     */
    protected function getResizedImagePath(string $originalPath, string $size): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $size . '.' . $pathInfo['extension'];
    }

    /**
     * Validate uploaded file
     *
     * @param UploadedFile $file
     * @param array $allowedExtensions
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateFile(UploadedFile $file, array $allowedExtensions): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions)
            );
        }
    }
} 