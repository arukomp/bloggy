<?php

namespace Arukomp\Bloggy\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Arukomp\Bloggy\Http\Controllers\Controller;
use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;

class TrashedPostsController extends Controller
{
    public function index(PostType $postType)
    {
        $posts = $postType->posts()->onlyTrashed()->get();
        $activePostsCount = $postType->posts()->count();

        return view('admin.posts.index', compact('postType', 'posts', 'activePostsCount'));
    }
}
