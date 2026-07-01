<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBannerRequest extends FormRequest
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
        $bannerId = $this->route('banner')->id;

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('banners')->ignore($bannerId, 'id')],
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'position' => 'required|string|in:hero,category,brand,product,promotional,announcement',
            'banner_type' => 'required|string|in:slider,static,video,interactive','sort_order' => 'nullable|integer|min:0',
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
            'position.required' => 'Banner position is required.',
            'position.in' => 'Invalid banner position selected.',
            'banner_type.required' => 'Banner type is required.',
            'banner_type.in' => 'Invalid banner type selected.',
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
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}