<?php

namespace Arukomp\Bloggy\Providers;

use Arukomp\Bloggy\Http\ViewComposers\Admin\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('bloggy::layouts.admin.partials.sidebar', SidebarComposer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
