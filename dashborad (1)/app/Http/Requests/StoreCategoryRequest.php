<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        return [
            'category_name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'image' => [
                'required',
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

                        // Simple dimension check - minimum 300x300px
                        if ($width < 300 || $height < 300) {
                            $fail('Category image must be at least 300x300 pixels. Current: ' . $width . 'x' . $height . ' pixels.');
                        }
                    }
                },
            ],
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
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
        // Generate slug from name if not provided
        if (!$this->slug && $this->category_name) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->category_name)
            ]);
        }

        // Set defaults
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
            'show_on_homepage' => $this->boolean('show_on_homepage', false),
            'sort_order' => $this->get('sort_order', 0),
        ]);
    }
}