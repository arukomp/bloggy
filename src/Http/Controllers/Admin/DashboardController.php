<?php

namespace Arukomp\Bloggy\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Arukomp\Bloggy\Http\Controllers\Controller;
use Arukomp\Bloggy\Models\PostType;

class DashboardController extends Controller
{
    public function index()
    {
        $postTypes = PostType::all();

        return view('bloggy::admin.dashboard', compact('postTypes'));
    }
}
