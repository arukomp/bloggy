<?php

namespace Arukomp\Bloggy\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;

class PostsController extends Controller
{
    public function show($postTypeSlug, $postIdOrSlug)
    {
        $redirect = false;

        $postType = PostType::where('slug', $postTypeSlug)->first();
        $post = null;

        if (is_null($postType)) {
            $postType = PostType::find($postTypeSlug);
            $redirect = true;
        }

        if (!is_null($postType)) {
            $post = $postType->posts()->when(Auth::check(), function ($query) {
                $query->withTrashed();
            })->where('slug', $postIdOrSlug)->first();
    
            if (is_null($post)) {
                $post = Post::find($postIdOrSlug);
                $redirect = true;
            }
        }

        if ($redirect && !is_null($post)) {
            return redirect($post->url);
        }

        if (is_null($postType) || is_null($post) || (Auth::guest() && !$post->active)) {
            abort(404);
        }

        return view('bloggy::posts.show', compact('post'));
    }
}
