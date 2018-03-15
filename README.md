# Bloggy!

## Installation

1. Install the package via Composer

```
$ composer require arukomp/bloggy
```

2. Publish Bloggy config

```
$ php artisan vendor:publish --tag=bloggy_config
```

3. Check the `config/bloggy.php` config and ensure you're ok with the assets path. Change if necessary.

4. Publish Bloggy Assets

```
php artisan vendor:publish --tag=bloggy_assets
```

5. Serve your Laravel app and go to `/admin/dashboard`.