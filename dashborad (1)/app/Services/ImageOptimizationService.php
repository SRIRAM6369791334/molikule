<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    protected $defaultSizes = [
        'thumbnail' => [150, 150],
        'small' => [300, 300],
        'medium' => [600, 600],
        'large' => [1200, 1200]
    ];

    protected $quality = 85;
    protected $allowedTypes = ['jpeg', 'jpg', 'png', 'webp'];
    protected $maxFileSize = 8192; // 8MB

    /**
     * Optimize and store image with multiple sizes
     */
    public function optimizeAndStore(UploadedFile $file, $path, $options = [])
    {
        $sizes = $options['sizes'] ?? $this->defaultSizes;
        $quality = $options['quality'] ?? $this->quality;
        $format = $options['format'] ?? 'webp';

        // Validate file
        $this->validateFile($file);

        // Load image using GD
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $sourceImage = $this->loadImage($file->getPathname());
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        $storedImages = [];
        
        foreach ($sizes as $sizeName => $dimensions) {
            [$width, $height] = $dimensions;
            
            // Resize with proper aspect ratio
            $resizedImage = imagecreatetruecolor($width, $height);
            
            // Preserve transparency for PNG/WebP
            if (in_array($format, ['png', 'webp'])) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefill($resizedImage, 0, 0, $transparent);
            }
            
            // Calculate aspect ratio
            $aspectRatio = $originalWidth / $originalHeight;
            $targetAspectRatio = $width / $height;
            
            if ($aspectRatio > $targetAspectRatio) {
                // Image is wider, fit to height
                $newWidth = $height * $aspectRatio;
                $newHeight = $height;
            } else {
                // Image is taller, fit to width
                $newWidth = $width;
                $newHeight = $width / $aspectRatio;
            }
            
            // Calculate positioning to center image
            $x = ($width - $newWidth) / 2;
            $y = ($height - $newHeight) / 2;
            
            // Resize and copy
            imagecopyresampled($resizedImage, $sourceImage, $x, $y, 0, 0, 
                             $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Generate filename
            $filename = $this->generateFilename($file, $sizeName, $format);
            $fullPath = $path . '/' . $filename;
            
            // Store optimized image
            $this->saveImage($resizedImage, storage_path('app/public/' . $fullPath), $format, $quality);
            $storedImages[$sizeName] = $fullPath;
            
            // Free memory
            imagedestroy($resizedImage);
        }
        
        // Store original with optimization
        $originalPath = $path . '/' . $this->generateFilename($file, 'original', $format);
        $this->saveImage($sourceImage, storage_path('app/public/' . $originalPath), $format, $quality - 10);
        $storedImages['original'] = $originalPath;
        
        // Free original image memory
        imagedestroy($sourceImage);
        
        return $storedImages;
    }

    /**
     * Generate responsive image URLs
     */
    public function getResponsiveUrls($path, $sizes = ['small', 'medium', 'large'])
    {
        $urls = [];
        $pathInfo = pathinfo($path);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'webp';
        
        foreach ($sizes as $size) {
            $sizedFilename = $filename . "_{$size}.{$extension}";
            $sizedPath = $directory . '/' . $sizedFilename;
            
            if (Storage::disk('public')->exists($sizedPath)) {
                $urls[$size] = Storage::disk('public')->url($sizedPath);
            }
        }
        
        return $urls;
    }

    /**
     * Generate srcset attribute for responsive images
     */
    public function generateSrcSet($basePath, $sizes = ['small', 'medium', 'large'])
    {
        $srcset = [];
        $pathInfo = pathinfo($basePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'webp';
        
        $widths = [
            'small' => 300,
            'medium' => 600,
            'large' => 1200,
            'original' => 1200
        ];
        
        foreach ($sizes as $size) {
            $sizedFilename = $filename . "_{$size}.{$extension}";
            $sizedPath = $directory . '/' . $sizedFilename;
            
            if (Storage::disk('public')->exists($sizedPath)) {
                $url = Storage::disk('public')->url($sizedPath);
                $width = $widths[$size] ?? 600;
                $srcset[] = "{$url} {$width}w";
            }
        }
        
        return implode(', ', $srcset);
    }

    /**
     * Clean up unused images
     */
    public function cleanupUnusedImages($modelId, $modelType, $currentImagePaths = [])
    {
        $modelClass = 'App\\Models\\' . $modelType;
        if (!class_exists($modelClass)) {
            return;
        }

        $model = $modelClass::find($modelId);
        if (!$model) {
            return;
        }

        // Get all image paths from the model
        $imageFields = ['image_url', 'banner_url', 'logo_url'];
        
        foreach ($imageFields as $field) {
            if (isset($model->$field) && !in_array($model->$field, $currentImagePaths)) {
                // Delete main image
                if (Storage::disk('public')->exists($model->$field)) {
                    Storage::disk('public')->delete($model->$field);
                }
                
                // Delete optimized versions
                $pathInfo = pathinfo($model->$field);
                $directory = $pathInfo['dirname'];
                $filename = $pathInfo['filename'];
                $extension = $pathInfo['extension'] ?? 'webp';
                
                $sizes = ['thumbnail', 'small', 'medium', 'large'];
                foreach ($sizes as $size) {
                    $sizedPath = $directory . '/' . $filename . "_{$size}.{$extension}";
                    if (Storage::disk('public')->exists($sizedPath)) {
                        Storage::disk('public')->delete($sizedPath);
                    }
                }
            }
        }
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file)
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize * 1024) {
            throw new \Exception("File size exceeds {$this->maxFileSize}KB limit");
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', $this->allowedTypes));
        }

        // Check if it's actually an image
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \Exception('File is not a valid image');
        }
    }

    /**
     * Load image from file
     */
    protected function loadImage($filePath)
    {
        $imageInfo = getimagesize($filePath);
        $format = $imageInfo[2];
        
        switch ($format) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filePath);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filePath);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($filePath);
            default:
                throw new \Exception('Unsupported image format');
        }
    }

    /**
     * Save image to file
     */
    protected function saveImage($image, $filePath, $format, $quality)
    {
        // Ensure directory exists
        $directory = dirname($filePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        switch ($format) {
            case 'jpeg':
            case 'jpg':
                return imagejpeg($image, $filePath, $quality);
            case 'png':
                return imagepng($image, $filePath, 9 - intval($quality / 10));
            case 'webp':
                return imagewebp($image, $filePath, $quality);
            default:
                throw new \Exception('Unsupported output format');
        }
    }

    /**
     * Generate filename
     */
    protected function generateFilename($file, $size, $format)
    {
        $hash = md5($file->getClientOriginalName() . $file->getSize() . time());
        $extension = $format;
        return "{$hash}_{$size}.{$extension}";
    }
}