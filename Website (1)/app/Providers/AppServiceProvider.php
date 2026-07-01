<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $headerCategories = Schema::hasTable('categories')
                ? Category::active()->with('children')->whereNull('parent_id')->get()
                : collect();

            $view->with('headerCategories', $headerCategories);
        });
    }
}
