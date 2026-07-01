<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'brand_name' => 'required|string|max:255|unique:brands',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string|max:2000',
            'logo' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Get image dimensions
                        $imageInfo = getimagesize($value->getPathname());
                        if (!$imageInfo) {
                            $fail('Invalid logo image file.');
                            return;
                        }

                        $width = $imageInfo[0];
                        $height = $imageInfo[1];

                        // Simple dimension check - minimum 200x200px
                        if ($width < 200 || $height < 200) {
                            $fail('Logo must be at least 200x200 pixels. Current: ' . $width . 'x' . $height . ' pixels.');
                        }
                    }
                },
            ],
            'logo_url' => 'nullable|url',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            
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
            'youtube_url' => 'nullable|url',
            'pinterest_url' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            
            // Brand colors and theme
            'primary_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'secondary_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'background_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            
            // Brand story and content
            'brand_story' => 'nullable|string|max:5000',
            'founding_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'founder_name' => 'nullable|string|max:255',
            'headquarters' => 'nullable|string|max:255',
            
            // Performance and analytics
            'view_count' => 'nullable|integer|min:0',
            'click_count' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'brand_name.required' => 'Brand name is required.',
            'brand_name.unique' => 'A brand with this name already exists.',
            'slug.unique' => 'This slug is already taken.',
            'website_url.url' => 'Website URL must be a valid URL.',
            'facebook_url.url' => 'Facebook URL must be a valid URL.',
            'twitter_url.url' => 'Twitter URL must be a valid URL.',
            'instagram_url.url' => 'Instagram URL must be a valid URL.',
            'linkedin_url.url' => 'LinkedIn URL must be a valid URL.',
            'youtube_url.url' => 'YouTube URL must be a valid URL.',
            'pinterest_url.url' => 'Pinterest URL must be a valid URL.',
            'canonical_url.url' => 'Canonical URL must be a valid URL.',
            'contact_email.email' => 'Contact email must be a valid email address.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
            'primary_color.regex' => 'Primary color must be a valid hex color code.',
            'secondary_color.regex' => 'Secondary color must be a valid hex color code.',
            'background_color.regex' => 'Background color must be a valid hex color code.',
            'founding_year.min' => 'Founding year cannot be before 1800.',
            'founding_year.max' => 'Founding year cannot be in the future.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generate slug from name if not provided
        if (!$this->slug && $this->brand_name) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->brand_name)
            ]);
        }

        // Set defaults
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
            'sort_order' => $this->get('sort_order', 0),
            'view_count' => $this->get('view_count', 0),
            'click_count' => $this->get('click_count', 0),
        ]);
    }
}