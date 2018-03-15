<?php

namespace Arukomp\Bloggy;

use Illuminate\Support\ServiceProvider;

class BloggyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadHelpers();
        $this->loadMigrations();
        $this->loadViews();
        $this->mapBindings();
        $this->mapRoutes();
        $this->mapCommands();
        $this->registerPublishableResources();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../publishable/config/bloggy.php',
            'bloggy'
        );
    }

    /**
     * Load the Bloggy package helpers.
     *
     * @return void
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Map the Bloggy package routes.
     *
     * @return void
     */
    protected function mapRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Load the Bloggy package migrations.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Load the Bloggy package views.
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bloggy');
    }

    /**
     * Map the Bloggy package console commands.
     *
     * @return void
     */
    protected function mapCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    /**
     * Publish Bloggy assets.
     *
     * @return void
     */
    protected function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/publishable';

        $publishable = [
            'bloggy_assets' => [
                "{$publishablePath}/assets/" => public_path(config('bloggy.assets_path')),
                dirname(__DIR__).'/fonts/'   => public_path(config('bloggy.fonts_path')),
            ],
            'bloggy_config' => [
                "{$publishablePath}/config/bloggy.php" => config_path('bloggy.php'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    /**
     * Map Service container bindings for Bloggy package.
     *
     * @return void
     */
    protected function mapBindings()
    {
        //
    }
}
