<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\HeaderComposer;

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
        View::composer(
            'layout.shop-header',
            HeaderComposer::class
        );

        // Inject permission data into the admin layout (and thus the sidebar partial)
        View::composer(
            'layout.admin',
            \App\View\Composers\SidebarComposer::class
        );
    }
}
