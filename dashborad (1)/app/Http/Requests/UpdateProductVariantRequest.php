<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $variant = $this->route('product_variant');
        $variantId = is_object($variant) ? $variant->id : $variant;

        return [
            'variant_name' => 'required|string|max:255',
            'value'        => 'required|string|max:255',
            'variant_unit'  => 'required|string|max:50',
            'variant_type'  => 'nullable|string|max:50',
            'mrp_price'     => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock'      => 'required|integer|min:0',
            'variant_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:width=1080,height=1080',
            'active'         => 'nullable|boolean',
            'is_featured'    => 'nullable|boolean',
            'is_trending'    => 'nullable|boolean',
            'discount_type'  => 'nullable|in:percentage,flat',
            'discount_value' => 'nullable|numeric|min:0',
            'sku'            => ['required', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($variantId)],
        ];
    }
}
