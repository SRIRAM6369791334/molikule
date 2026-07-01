<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Banner;

class StoreBannerRequest extends FormRequest
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
            'banner_limit' => [
                function ($attribute, $value, $fail) {
                    $currentBannerCount = Banner::count();
                    if ($currentBannerCount >= 6) {
                        $fail('Maximum of 6 banners allowed. Please delete an existing banner before creating a new one.');
                    }
                },
            ],
            'title' => 'nullable|string|max:50|unique:banners',
            'subtitle' => 'nullable|string|max:150',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'position' => 'nullable|string',
            'banner_type' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            
            // Scheduling
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            
            // Targeting and display
            'target_categories' => 'nullable|array',
            'target_categories.*' => 'exists:categories,category_id',
            'target_brands' => 'nullable|array',
            'target_brands.*' => 'exists:brands,brand_id',
            'target_products' => 'nullable|array',
            'target_products.*' => 'exists:products,product_id',
            'user_roles' => 'nullable|array',
            'device_types' => 'nullable|array|in:desktop,mobile,tablet',
            
            // Performance tracking
            'impression_count' => 'nullable|integer|min:0',
            'click_count' => 'nullable|integer|min:0',
            'conversion_count' => 'nullable|integer|min:0',
            
            // SEO and metadata
            'alt_text' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            
            // Video-specific fields
            'video_url' => 'nullable|url',
            'video_autoplay' => 'boolean',
            'video_muted' => 'boolean',
            'video_controls' => 'boolean',
            'video_duration' => 'nullable|integer|min:1|max:300',
            
            // Interactive banner fields
            'cta_text' => 'nullable|string|max:100',
            'cta_link' => 'nullable|url',
            'background_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'text_color' => 'nullable|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Banner title is required.',
            'title.unique' => 'A banner with this title already exists.',
            'subtitle.required' => 'Subtitle is required.',
            'image.required' => 'Banner image is required (1521x580px, max 5MB).',
            'image.image' => 'Banner image must be a valid image file.',
            'image.mimes' => 'Banner image must be JPEG, PNG, GIF, or WebP format.',
            'image.max' => 'Banner image must not exceed 5MB.',
            'minimage.required' => 'Min image is required (1024x1024px, max 5MB).',
            'minimage.image' => 'Min image must be a valid image file.',
            'minimage.mimes' => 'Min image must be JPEG, PNG, GIF, or WebP format.',
            'minimage.max' => 'Min image must not exceed 5MB.',
            'link_url.url' => 'Link URL must be a valid URL.',
            'cta_link.url' => 'CTA link must be a valid URL.',
            'video_url.url' => 'Video URL must be a valid URL.',
            'expires_at.after_or_equal' => 'Expiration date must be after or equal to start date.',
            'target_categories.*.exists' => 'Selected target category does not exist.',
            'target_brands.*.exists' => 'Selected target brand does not exist.',
            'target_products.*.exists' => 'Selected target product does not exist.',
            'device_types.in' => 'Invalid device type selected.',
            'background_color.regex' => 'Background color must be a valid hex color code.',
            'text_color.regex' => 'Text color must be a valid hex color code.',
            'video_duration.min' => 'Video duration must be at least 1 second.',
            'video_duration.max' => 'Video duration cannot exceed 300 seconds.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set defaults
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
            'sort_order' => $this->get('sort_order', 0),
            'impression_count' => $this->get('impression_count', 0),
            'click_count' => $this->get('click_count', 0),
            'conversion_count' => $this->get('conversion_count', 0),
            'video_autoplay' => $this->boolean('video_autoplay', false),
            'video_muted' => $this->boolean('video_muted', true),
            'video_controls' => $this->boolean('video_controls', true),
        ]);
    }
}
