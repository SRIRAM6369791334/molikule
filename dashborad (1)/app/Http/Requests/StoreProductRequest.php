<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $isAuthorized = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager());
        
        \Log::info('StoreProductRequest Authorization Check', [
            'is_authorized' => $isAuthorized,
            'user' => auth()->user() ? auth()->user()->email : 'Guest',
            'role' => auth()->user() ? auth()->user()->user_type : 'None',
            'path' => $this->path()
        ]);

        return $isAuthorized;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string|max:10000',
            'short_description' => 'required|string|max:500',
            
            // Mandatory First Variant Fields (Atomic Creation)
            'variant_value' => 'required|string|max:100', // Flavor
            'variant_unit' => 'required|string|max:50',  // Volume e.g. 500ml
            'variant_mrp' => 'required|numeric|min:0.01|max:999999.99',
            'variant_stock' => 'required|integer|min:0',
            'variant_sku' => 'required|string|max:100|unique:product_variants,sku',
            'variant_discount_type' => 'nullable|in:percentage,flat',
            'variant_discount_value' => 'nullable|numeric|min:0',
            
            'category_id' => 'required|exists:categories,category_id',
            'brand_id' => 'required|exists:brands,brand_id',
            
            // Media
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:width=1080,height=1080',
            'gallery_images'   => 'nullable|array|max:4',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120|dimensions:width=1080,height=1080',

            // Advanced Info
            'part_number' => 'nullable|string|max:100',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'made_in' => 'nullable|string|max:100',
            'warranty' => 'nullable|string|max:100',
            'weight' => 'required|numeric|min:0',
            'weight_unit' => 'required|string|in:kg,g,lb,oz',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'dimension_unit' => 'required|string|in:cm,mm,in,ft',
            
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'active' => 'boolean',
            
            // Meta Fields
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
        ];
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
            'brand_id.required' => 'Please select a brand.',
            'brand_id.exists' => 'Selected brand does not exist.',
            'stock_quantity.required' => 'Initial stock quantity is required.',
            'stock_quantity.numeric' => 'Initial stock must be a valid number.',
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
        \Log::debug('StoreProductRequest: prepareForValidation', [
            'name' => $this->name,
            'mrp_price' => $this->mrp_price,
            'category_id' => $this->category_id
        ]);

        // Don't auto-generate slug here. Let the Model's booted() method 
        // handle unique slug generation to avoid validation conflicts 
        // when a product name is reused or similar.

        // Clean up tags
        if ($this->tags && is_string($this->tags)) {
            $this->merge([
                'tags' => array_map('trim', explode(',', $this->tags))
            ]);
        }

        // Set defaults
        $this->merge([
            'active' => $this->boolean('active', true),
            'track_quantity' => $this->boolean('track_quantity', true),
            'is_featured' => $this->boolean('is_featured', false),
            'low_stock_threshold' => $this->input('low_stock_threshold', 5),
        ]);
    }
}
