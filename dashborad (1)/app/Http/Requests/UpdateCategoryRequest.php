<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager());
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')->category_id;

        return [
            'category_name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId, 'category_id')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId, 'category_id')],
            'description' => 'nullable|string|max:1000',
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Get image dimensions
                        $imageInfo = getimagesize($value->getPathname());
                        if (!$imageInfo) {
                            $fail('Invalid category image file.');
                            return;
                        }

                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        $fileSize = $value->getSize();

                        // Category image should be square-ish (allow 1:1 to 4:3)
                        if ($width < 300 || $height < 300) {
                            $fail('Category image must be at least 300x300 pixels.');
                        }

                        // Aspect ratio validation for category images (should be fairly square)
                        $ratio = $width / $height;
                        if ($ratio < 0.75 || $ratio > 1.33) {
                            $fail('Category image aspect ratio must be between 3:4 and 4:3. Current ratio: ' . round($ratio, 2));
                        }

                        // Quality validation
                        $pixels = $width * $height;
                        $quality = $pixels / $fileSize;

                        if ($quality < 6) {
                            $fail('Category image quality is too low. Please upload a higher quality image.');
                        }

                        // Resolution limit
                        if ($pixels > 8000000) { // 8MP limit for category images
                            $fail('Category image resolution is too high. Maximum 8MP allowed.');
                        }
                    }
                },
            ],
            'banner' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:8192',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Get image dimensions
                        $imageInfo = getimagesize($value->getPathname());
                        if (!$imageInfo) {
                            $fail('Invalid category banner file.');
                            return;
                        }

                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        $fileSize = $value->getSize();

                        // Category banner should be wide (allow 16:9 to 3:1)
                        if ($width < 800 || $height < 200) {
                            $fail('Category banner must be at least 800x200 pixels.');
                        }

                        // Aspect ratio validation for category banners (wide format)
                        $ratio = $width / $height;
                        if ($ratio < 3 || $ratio > 8) {
                            $fail('Category banner aspect ratio must be between 3:1 and 8:1. Current ratio: ' . round($ratio, 2));
                        }

                        // Quality validation
                        $pixels = $width * $height;
                        $quality = $pixels / $fileSize;

                        if ($quality < 8) {
                            $fail('Category banner quality is too low. Please upload a higher quality image.');
                        }

                        // Resolution limit
                        if ($pixels > 20000000) { // 20MP limit for category banners
                            $fail('Category banner resolution is too high. Maximum 20MP allowed.');
                        }
                    }
                },
            ],
            'icon' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:1024',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Get image dimensions
                        $imageInfo = getimagesize($value->getPathname());
                        if (!$imageInfo) {
                            $fail('Invalid category icon file.');
                            return;
                        }

                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        $fileSize = $value->getSize();

                        // Category icon should be small and square
                        if ($width < 32 || $height < 32) {
                            $fail('Category icon must be at least 32x32 pixels.');
                        }

                        if ($width > 256 || $height > 256) {
                            $fail('Category icon cannot exceed 256x256 pixels.');
                        }

                        // Aspect ratio validation for icons (should be square)
                        $ratio = $width / $height;
                        if ($ratio < 0.8 || $ratio > 1.25) {
                            $fail('Category icon aspect ratio must be between 4:5 and 5:4. Current ratio: ' . round($ratio, 2));
                        }

                        // Quality validation
                        $pixels = $width * $height;
                        $quality = $pixels / $fileSize;

                        if ($quality < 3) {
                            $fail('Category icon quality is too low. Please upload a higher quality image.');
                        }

                        // Resolution limit
                        if ($pixels > 100000) { // 100K pixels limit for icons
                            $fail('Category icon resolution is too high. Maximum 100K pixels allowed.');
                        }
                    }
                },
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_on_homepage' => 'boolean',
            
            // SEO fields
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'structured_data' => 'nullable|array',
            
            // Social media and contact info
            'website_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_name.required' => 'Category name is required.',
            'category_name.unique' => 'A category with this name already exists.',
            'slug.unique' => 'This slug is already taken.',
            'website_url.url' => 'Website URL must be a valid URL.',
            'facebook_url.url' => 'Facebook URL must be a valid URL.',
            'twitter_url.url' => 'Twitter URL must be a valid URL.',
            'instagram_url.url' => 'Instagram URL must be a valid URL.',
            'linkedin_url.url' => 'LinkedIn URL must be a valid URL.',
            'canonical_url.url' => 'Canonical URL must be a valid URL.',
            'contact_email.email' => 'Contact email must be a valid email address.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set defaults
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
            'show_on_homepage' => $this->boolean('show_on_homepage'),
        ]);
    }
}