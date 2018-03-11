<?php

if (!function_exists('bloggy_asset')) {
    function bloggy_asset($path, $secure = null)
    {
        return asset(config('bloggy.assets_path') . '/' . $path, $secure);
    }
}
