const webpack = require('webpack');

let mix = require('laravel-mix');

mix.webpackConfig({
    plugins: [
        new webpack.IgnorePlugin(/^codemirror$/),
    ],
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'publishable/assets/js')
    .sass('resources/assets/sass/app.scss', 'publishable/assets/css');