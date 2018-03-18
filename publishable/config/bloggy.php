<?php

return [

    /*
     * You might want to separate Bloggy tables from your own
     * and make them easily distinguishable.
     *
     * This needs to be set BEFORE running the migrations
     * for this package.
     */
    'database_prefix' => env('BLOGGY_DATABASE_PREFIX', 'bloggy_'),

    /*
     * Bloggy routes prefix.
     *
     * Bloggy routes (posts, post types, admin section) will be
     * offset with this prefix
     */
    'routes_prefix' => env('BLOGGY_ROUTES_PREFIX', 'blog'),

    /*
     * The user class used as the author of posts created.
     *
     * It is assumed this class has a primary key named "id".
     */
    'user_class' => 'App\User',

    /*
     * The public path to which js/css assets will be published to.
     */
    'assets_path' => '/vendor/arukomp/bloggy/assets',

    /*
     * The public path to which fonts will be published to.
     */
    'fonts_path' => '/fonts',
];
