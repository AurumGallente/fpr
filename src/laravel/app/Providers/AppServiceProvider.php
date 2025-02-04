<?php

namespace App\Providers;

use App\Models\Text;
use Illuminate\Support\ServiceProvider;
use App\Observers\TextObserver;
use Illuminate\Support\Facades\Gate;
use App\Policies\Api\V1\ProjectPolicy as V1ProjectPolicy;
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

        Gate::define('v1-show', [V1ProjectPolicy::class, 'show']);
        Gate::define('v1-store', [V1ProjectPolicy::class, 'store']);
        Gate::define('v1-update', [V1ProjectPolicy::class, 'update']);
        Gate::define('v1-delete', [V1ProjectPolicy::class, 'delete']);
    }
}
