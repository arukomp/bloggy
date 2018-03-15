[![StyleCI](https://styleci.io/repos/124954313/shield?branch=master)](https://styleci.io/repos/124954313)

# Bloggy!

## Installation

Install the package via Composer:

```
$ composer require arukomp/bloggy
```

Publish Bloggy config:

```
$ php artisan vendor:publish --tag=bloggy_config
```

Check the `config/bloggy.php` config and ensure you're ok with the assets path. Change if necessary.

Publish Bloggy Assets:

```
php artisan vendor:publish --tag=bloggy_assets
```

Serve your Laravel app and go to `/admin/dashboard`.