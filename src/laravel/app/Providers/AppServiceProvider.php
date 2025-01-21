<?php

namespace App\Providers;

use App\Models\Text;
use Illuminate\Support\ServiceProvider;
use App\Observers\TextObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Text::observe(TextObserver::class);
    }
}
