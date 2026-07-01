<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get productId directly from URL - works for both /products/1/edit and /products/slug/edit
        $segment = $this->segment(2); // e.g., '1' or 'ld14-ultra-laundry-liquid'
        $productId = is_numeric($segment)
            ? (int) $segment
            : \App\Models\Product::where('slug', $segment)->value('product_id');

        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId, 'product_id')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId, 'product_id')],
            'description' => 'required|string|max:10000',
            'short_description' => 'required|string|max:500',
            'mrp_price' => 'nullable|numeric|min:0|max:999999.99',
            'compare_price' => 'nullable|numeric|min:0|max:999999.99',
            'cost_per_item' => 'nullable|numeric|min:0|max:999999.99',
            'stock_quantity' => 'nullable|integer|min:0|max:999999',
            'track_quantity' => 'nullable|boolean',
            'continue_selling_when_out_of_stock' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,category_id',
            'brand_id' => 'required|exists:brands,brand_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:width=1080,height=1080',
            'gallery_images'   => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:width=1080,height=1080',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'part_number' => 'nullable|string|max:100',
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('products', 'barcode')->ignore($productId, 'product_id')->whereNull('deleted_at')],
            'made_in' => 'nullable|string|max:100',
            'warranty' => 'nullable|string|max:100',
            'weight' => 'required|numeric|min:0|max:999999.99',
            'weight_unit' => 'required|string|in:kg,g,lb,oz',
            'length' => 'required|numeric|min:0|max:9999.99',
            'width' => 'required|numeric|min:0|max:9999.99',
            'height' => 'required|numeric|min:0|max:9999.99',
            'dimension_unit' => 'required|string|in:cm,mm,in,ft',
            'condition' => 'nullable|string|max:100',
            'dimension' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'active' => 'nullable|boolean',

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

            // Tags and custom fields
            'tags' => 'nullable|array|max:20',
            'tags.*' => 'string|max:50',
            'custom_fields' => 'nullable|array|max:10',

            // Variants validation
            'has_variants' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.variant_id' => 'sometimes|exists:product_variants,variant_id',
            'variants.*.variant_name' => 'nullable|string|max:255',
            'variants.*.mrp_price' => 'nullable|numeric|min:0|max:999999.99',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.active' => 'nullable|boolean',
            'variants.*.attributes' => 'sometimes|array',
            'variants.*.attributes.*' => 'sometimes|exists:variant_attribute_values,id',

            // For simple product (non-variant) or wizard-style edit
            'variant_id' => 'nullable|exists:product_variants,id',
            'variant_name' => 'nullable|string|max:255',
            'variant_value' => 'nullable|string|max:255',
            'variant_unit' => 'nullable|string|max:100',
            'variant_mrp' => 'nullable|numeric|min:0.01',
            'variant_stock' => 'nullable|integer|min:0',
            'variant_sku' => 'nullable|string|max:100',
            'variant_discount_type' => 'nullable|in:percentage,flat',
            'variant_discount_value' => 'nullable|numeric|min:0',
            'variant_type' => 'nullable|in:volume,flavour',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.unique' => 'A product with this name already exists.',
            'slug.unique' => 'This slug is already taken.',
            'barcode.unique' => 'This barcode is already taken.',
            'mrp_price.required' => 'Product price is required.',
            'mrp_price.min' => 'Price must be greater than zero.',
            'mrp_price.max' => 'Price cannot exceed ₹9,99,999.99.',
            'compare_price.gt' => 'Compare price must be greater than selling price.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'brand_id.exists' => 'Selected brand does not exist.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
            'tags.max' => 'Maximum 20 tags allowed.',
            'tags.*.max' => 'Each tag cannot exceed 50 characters.',
            'weight_unit.in' => 'Invalid weight unit selected.',
            'dimension_unit.in' => 'Invalid dimension unit selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up tags
        if ($this->tags && is_string($this->tags)) {
            $this->merge([
                'tags' => array_map('trim', explode(',', $this->tags))
            ]);
        }

        // Set boolean defaults explicitly so unchecked boxes = false
        $this->merge([
            'active'      => $this->boolean('active'),
            'track_quantity' => $this->boolean('track_quantity'),
            'is_featured' => $this->boolean('is_featured'),
            'is_trending' => $this->boolean('is_trending'),
        ]);
    }
}
