<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

trait HasSlug
{
    /**
     * Boot the HasSlug trait
     */
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            if ($model->shouldGenerateSlug() && empty($model->getSlug())) {
                $model->generateSlug();
            }
        });

        static::updating(function (Model $model) {
            if ($model->isDirty($model->getSlugColumn()) && empty($model->getSlug())) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Get the slug column name
     */
    public function getSlugColumn()
    {
        return 'slug';
    }

    /**
     * Get the slug column name (alias for compatibility)
     */
    public function getSlugColumnName()
    {
        return $this->getSlugColumn();
    }

    /**
     * Get the name column for slug generation
     */
    public function getNameColumn()
    {
        $nameColumns = ['name', 'title', 'brand_name', 'category_name'];
        
        foreach ($nameColumns as $column) {
            if ($this->hasColumn($this->table, $column)) {
                return $column;
            }
        }
        
        return null;
    }

    /**
     * Check if slug column exists
     */
    public function hasSlugColumn()
    {
        return $this->hasColumn($this->table, $this->getSlugColumn());
    }

    /**
     * Check if a column exists in the database
     */
    protected function hasColumn($table, $column)
    {
        try {
            return Schema::hasColumn($table, $column);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the slug value
     */
    public function getSlug()
    {
        if (!$this->hasSlugColumn()) {
            return null;
        }
        
        return $this->getAttribute($this->getSlugColumn());
    }

    /**
     * Set the slug value
     */
    public function setSlug($slug)
    {
        if ($this->hasSlugColumn()) {
            $this->setAttribute($this->getSlugColumn(), $slug);
        }
        
        return $this;
    }

    /**
     * Get the slug URL
     */
    public function getSlugUrl($route = null)
    {
        $slug = $this->getSlug();
        
        if (!$slug) {
            return route($route ?? $this->getDefaultRouteName(), $this);
        }
        
        return route($route ?? $this->getDefaultRouteName(), $slug);
    }

    /**
     * Get default route name for the model
     */
    protected function getDefaultRouteName()
    {
        $table = $this->getTable();
        return substr($table, 0, -1) . '.show'; // Remove 's' from table name
    }

    /**
     * Find by slug or ID
     */
    public static function findBySlugOrId($identifier)
    {
        if (is_numeric($identifier)) {
            return static::find($identifier);
        }
        
        $instance = new static();
        if ($instance->hasSlugColumn()) {
            return static::where('slug', $identifier)->first();
        }
        
        return null;
    }

    /**
     * Find by slug or fail
     */
    public static function findBySlugOrIdOrFail($identifier)
    {
        $model = static::findBySlugOrId($identifier);
        
        if (!$model) {
            abort(404, 'Model not found');
        }
        
        return $model;
    }

    /**
     * Get route key for route model binding
     */
    public function getRouteKey()
    {
        return $this->getSlug() ?? $this->getKey();
    }

    /**
     * Get route key name
     */
    public function getRouteKeyName()
    {
        return $this->hasSlugColumn() ? 'slug' : $this->getKeyName();
    }

    /**
     * Generate slug from name
     */
    public function generateSlug()
    {
        $nameColumn = $this->getNameColumn();
        
        if (!$nameColumn || !$this->hasSlugColumn()) {
            return;
        }
        
        $name = $this->$nameColumn;
        $slug = Str::slug($name);
        
        $this->setAttribute($this->getSlugColumn(), $this->makeSlugUnique($slug));
    }

    /**
     * Make slug unique
     */
    protected function makeSlugUnique($slug, $separator = '-')
    {
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExistsInDatabase($slug, $this->getKey())) {
            $slug = $originalSlug . $separator . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Check if slug exists in database
     */
    protected function slugExistsInDatabase($slug, $excludeId = null)
    {
        if (!$this->hasSlugColumn()) {
            return false;
        }
        
        $query = DB::table($this->getTable())->where('slug', $slug);
        
        if ($excludeId) {
            $query->where($this->getKeyName(), '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Should generate slug automatically
     */
    protected function shouldGenerateSlug()
    {
        return true; // Override in model if needed
    }

    /**
     * Scope to find by slug
     */
    public function scopeWhereSlug($query, $slug)
    {
        if ($this->hasSlugColumn()) {
            return $query->where('slug', $slug);
        }
        
        return $query; // Return unchanged query if no slug column
    }

    /**
     * Get SEO title attribute
     */
    public function getSeoTitleAttribute()
    {
        if ($this->hasColumn($this->table, 'meta_title') && $this->meta_title) {
            return $this->meta_title;
        }
        
        $nameColumn = $this->getNameColumn();
        return $nameColumn ? Str::limit($this->$nameColumn, 60) : 'Untitled';
    }

    /**
     * Get SEO description attribute
     */
    public function getSeoDescriptionAttribute()
    {
        if ($this->hasColumn($this->table, 'meta_description') && $this->meta_description) {
            return $this->meta_description;
        }
        
        if ($this->hasColumn($this->table, 'short_description') && $this->short_description) {
            return Str::limit($this->short_description, 160);
        }
        
        if ($this->hasColumn($this->table, 'description') && $this->description) {
            return Str::limit($this->description, 160);
        }
        
        return 'No description available';
    }

    /**
     * Get display name for the model
     */
    public function getDisplayNameAttribute()
    {
        $nameColumn = $this->getNameColumn();
        return $nameColumn ? $this->$nameColumn : 'Unnamed';
    }

    /**
     * Check if model has a valid slug
     */
    public function hasValidSlug()
    {
        return $this->hasSlugColumn() && !empty($this->getSlug());
    }

    /**
     * Update slug to current name
     */
    public function updateSlugToCurrentName()
    {
        if ($this->hasSlugColumn()) {
            $this->generateSlug();
            $this->save();
        }
    }
}