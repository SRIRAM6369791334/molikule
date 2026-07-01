<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HasEnhancedFields
{
    /**
     * Check if a column exists in the database
     */
    protected function hasColumn($table, $column)
    {
        if (!Schema::hasTable($table)) {
            return false;
        }
        
        try {
            return Schema::hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get a field value with fallback for missing columns
     */
    protected function getField($attribute, $default = null)
    {
        if ($this->hasColumn($this->table, $attribute)) {
            return $this->getAttribute($attribute);
        }
        
        return $default ?? $this->getDefaultForField($attribute);
    }

    /**
     * Set a field value safely
     */
    protected function setField($attribute, $value)
    {
        if ($this->hasColumn($this->table, $attribute)) {
            $this->setAttribute($attribute, $value);
            return true;
        }
        
        return false;
    }

    /**
     * Get default values for enhanced fields
     */
    protected function getDefaultForField($attribute)
    {
        $defaults = [
            'slug' => null,
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'canonical_url' => null,
            'short_description' => null,
            'compare_price' => null,
            'cost_per_item' => null,
            'track_quantity' => true,
            'continue_selling_when_out_of_stock' => false,
            'barcode' => null,
            'weight_unit' => 'kg',
            'length' => null,
            'width' => null,
            'height' => null,
            'dimension_unit' => 'cm',
            'tags' => null,
            'custom_fields' => null,
            'view_count' => 0,
            'is_featured' => false,
            'product_count' => 0,
            'website_url' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'address' => null,
            'social_links' => [],
            'brand_colors' => [],
            'brand_story' => null,
            'banner_type' => 'promotional',
            'subtitle' => null,
            'mini_image_url' => null,
            'target_type' => null,
            'target_id' => null,
            'target_url' => null,
            'button_text' => null,
            'background_color' => null,
            'text_color' => null,
            'starts_at' => null,
            'expires_at' => null,
            'show_on_hover' => false,
            'display_duration' => null,
            'impression_count' => 0,
            'click_count' => 0,
            'ctr' => 0,
            'alt_text' => null,
            'css_class' => null,
            'custom_data' => [],
            'content' => null,
            'icon' => null,
            'banner_image' => null,
        ];

        return $defaults[$attribute] ?? null;
    }
}