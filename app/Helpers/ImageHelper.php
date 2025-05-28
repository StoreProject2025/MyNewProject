<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Supported image types
     *
     * @var array
     */
    protected static $supportedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Resize image
     *
     * @param string|UploadedFile $image
     * @param int $width
     * @param int $height
     * @param bool $aspectRatio
     * @return \Intervention\Image\Image
     */
    public static function resize($image, int $width, int $height, bool $aspectRatio = true)
    {
        $img = Image::make($image);
        
        if ($aspectRatio) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $img->resize($width, $height);
        }

        return $img;
    }

    /**
     * Crop image
     *
     * @param string|UploadedFile $image
     * @param int $width
     * @param int $height
     * @param int|null $x
     * @param int|null $y
     * @return \Intervention\Image\Image
     */
    public static function crop($image, int $width, int $height, ?int $x = null, ?int $y = null)
    {
        $img = Image::make($image);
        
        if ($x === null && $y === null) {
            return $img->fit($width, $height);
        }

        return $img->crop($width, $height, $x, $y);
    }

    /**
     * Add watermark to image
     *
     * @param string|UploadedFile $image
     * @param string $watermark
     * @param string $position top-left|top|top-right|left|center|right|bottom-left|bottom|bottom-right
     * @param int $opacity
     * @return \Intervention\Image\Image
     */
    public static function addWatermark($image, string $watermark, string $position = 'bottom-right', int $opacity = 50)
    {
        $img = Image::make($image);
        $watermarkImg = Image::make($watermark);
        
        $watermarkImg->opacity($opacity);

        $x = 0;
        $y = 0;

        switch ($position) {
            case 'top-left':
                $x = 10;
                $y = 10;
                break;
            case 'top':
                $x = ($img->width() - $watermarkImg->width()) / 2;
                $y = 10;
                break;
            case 'top-right':
                $x = $img->width() - $watermarkImg->width() - 10;
                $y = 10;
                break;
            case 'left':
                $x = 10;
                $y = ($img->height() - $watermarkImg->height()) / 2;
                break;
            case 'center':
                $x = ($img->width() - $watermarkImg->width()) / 2;
                $y = ($img->height() - $watermarkImg->height()) / 2;
                break;
            case 'right':
                $x = $img->width() - $watermarkImg->width() - 10;
                $y = ($img->height() - $watermarkImg->height()) / 2;
                break;
            case 'bottom-left':
                $x = 10;
                $y = $img->height() - $watermarkImg->height() - 10;
                break;
            case 'bottom':
                $x = ($img->width() - $watermarkImg->width()) / 2;
                $y = $img->height() - $watermarkImg->height() - 10;
                break;
            default: // bottom-right
                $x = $img->width() - $watermarkImg->width() - 10;
                $y = $img->height() - $watermarkImg->height() - 10;
        }

        return $img->insert($watermarkImg, 'top-left', (int)$x, (int)$y);
    }

    /**
     * Convert image to grayscale
     *
     * @param string|UploadedFile $image
     * @return \Intervention\Image\Image
     */
    public static function toGrayscale($image)
    {
        return Image::make($image)->greyscale();
    }

    /**
     * Adjust image brightness
     *
     * @param string|UploadedFile $image
     * @param int $level -100 to 100
     * @return \Intervention\Image\Image
     */
    public static function adjustBrightness($image, int $level)
    {
        return Image::make($image)->brightness($level);
    }

    /**
     * Adjust image contrast
     *
     * @param string|UploadedFile $image
     * @param int $level -100 to 100
     * @return \Intervention\Image\Image
     */
    public static function adjustContrast($image, int $level)
    {
        return Image::make($image)->contrast($level);
    }

    /**
     * Rotate image
     *
     * @param string|UploadedFile $image
     * @param float $angle
     * @param string|array $bgcolor
     * @return \Intervention\Image\Image
     */
    public static function rotate($image, float $angle, $bgcolor = '#ffffff')
    {
        return Image::make($image)->rotate($angle, $bgcolor);
    }

    /**
     * Flip image
     *
     * @param string|UploadedFile $image
     * @param string $mode horizontal|vertical|both
     * @return \Intervention\Image\Image
     */
    public static function flip($image, string $mode = 'horizontal')
    {
        $img = Image::make($image);

        switch ($mode) {
            case 'vertical':
                return $img->flip('v');
            case 'both':
                return $img->flip('v')->flip('h');
            default:
                return $img->flip('h');
        }
    }

    /**
     * Get image information
     *
     * @param string|UploadedFile $image
     * @return array
     */
    public static function getInfo($image): array
    {
        $img = Image::make($image);

        return [
            'width' => $img->width(),
            'height' => $img->height(),
            'mime' => $img->mime(),
            'extension' => $img->extension,
            'filesize' => $img->filesize()
        ];
    }

    /**
     * Check if file is an image
     *
     * @param string|UploadedFile $file
     * @return bool
     */
    public static function isImage($file): bool
    {
        if ($file instanceof UploadedFile) {
            return in_array(strtolower($file->getClientOriginalExtension()), self::$supportedTypes);
        }

        return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), self::$supportedTypes);
    }

    /**
     * Generate image thumbnail
     *
     * @param string|UploadedFile $image
     * @param int $width
     * @param int $height
     * @param string $method fit|resize
     * @return \Intervention\Image\Image
     */
    public static function thumbnail($image, int $width, int $height, string $method = 'fit')
    {
        $img = Image::make($image);

        if ($method === 'fit') {
            return $img->fit($width, $height);
        }

        return $img->resize($width, $height);
    }
} 