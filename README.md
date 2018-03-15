[![Build Status](https://travis-ci.org/arukomp/bloggy.svg?branch=master)](https://travis-ci.org/arukomp/bloggy) [![StyleCI](https://styleci.io/repos/124954313/shield?branch=master)](https://styleci.io/repos/124954313) [![Maintainability](https://api.codeclimate.com/v1/badges/b7ae2f8bb34bc281dcce/maintainability)](https://codeclimate.com/github/arukomp/bloggy/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/b7ae2f8bb34bc281dcce/test_coverage)](https://codeclimate.com/github/arukomp/bloggy/test_coverage)

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