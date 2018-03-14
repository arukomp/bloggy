<?php

namespace Arukomp\Bloggy\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->withFactories(__DIR__ . '/../database/factories');

        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('bloggy.database_prefix', '');
        $app['config']->set('bloggy.user_class', \Arukomp\Bloggy\Tests\Stubs\User::class);
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arukomp\Bloggy\Providers\ViewComposerServiceProvider::class,
            \Arukomp\Bloggy\BloggyServiceProvider::class,
        ];
    }
}
