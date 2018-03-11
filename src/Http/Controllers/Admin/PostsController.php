<?php

namespace Arukomp\Bloggy\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Arukomp\Bloggy\Http\Controllers\Controller;
use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;

class PostsController extends Controller
{
    public function index(PostType $postType)
    {
        $posts = $postType->posts;
        $trashedPostsCount = $postType->posts()->onlyTrashed()->count();

        return view('bloggy::admin.posts.index', compact('postType', 'posts', 'trashedPostsCount'));
    }

    public function create(PostType $postType)
    {
        return view('bloggy::admin.posts.create', compact('postType'));
    }

    public function store(PostType $postType, Request $request)
    {
        $request->merge(['post_type_id' => $postType->id]);
        $post = Post::make($request->except(['excerpt', 'slug']));
        $post->fill($request->only(['excerpt', 'slug']));

        if ($request->has('publish')) {
            $post = $post->publish();
        } else {
            $post = $post->saveDraft();
        }

        return redirect()->route('admin.posts.edit', $post)->with('success', $this->getPostCreatedMessage($post));
    }

    public function edit($post)
    {
        $post = Post::withTrashed()->find($post);
        $postType = $post->type;

        $postsSharingSlug = collect([]);
        if (!$post->trashed() && $post->active) {
            $postsSharingSlug = Post::where('slug', $post->slug)
                ->where('id', '<>', $post->id)
                ->where('active', true)
                ->where('post_type_id', $post->post_type_id)
                ->get();
        }

        return view('bloggy::admin.posts.edit', compact('post', 'postType', 'postsSharingSlug'));
    }

    public function update(Post $post, Request $request)
    {
        $post->fill($request->except(['excerpt', 'slug']));
        $post->fill($request->only(['excerpt', 'slug']));

        if ($request->has('publish')) {
            $post = $post->publish();
        } else {
            $post = $post->saveDraft();
        }

        return redirect()->route('admin.posts.edit', $post)->with('success', $this->getPostUpdatedMessage($post));
    }

    public function publish(Post $post)
    {
        $post->withoutHistory()->publish();

        return redirect()->back()->with('success', $this->getPostPublishedMessage($post));
    }

    public function unpublish(Post $post)
    {
        $post->withoutHistory()->unpublish();

        return redirect()->back()->with('success', $this->getPostUnpublishedMessage($post));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->back()->with('success', $this->getPostDeletedMessage($post));
    }

    public function restore($post)
    {
        $post = tap(Post::withTrashed()->find($post)->withoutHistory())->restore();

        return redirect()->back()->with('success', $this->getPostRestoredMessage($post));
    }

    protected function getPostCreatedMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' created successfully!' . $this->getAlertButtonsFor($post);
    }

    protected function getPostUpdatedMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' updated successfully!' . $this->getAlertButtonsFor($post);
    }

    protected function getPostPublishedMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' has been published.' . $this->getAlertButtonsFor($post);
    }

    protected function getPostUnpublishedMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' is no longer published.' . $this->getAlertButtonsFor($post);
    }

    protected function getPostDeletedMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' has been trashed.' . $this->getAlertButtonsFor($post);
    }

    protected function getPostRestoredMessage($post)
    {
        return 'Post ' . $this->getPostLink($post) .
            ' has been restored.' . $this->getAlertButtonsFor($post);
    }

    protected function getPostLink($post)
    {
        return '<a class="alert-link" href="' . route('admin.posts.edit', $post) . '">' . str_limit($post->title, 50) . '</a>';
    }

    protected function getAlertButtonsFor($post)
    {
        return '<span class="float-right">' .
                '<a href="'. route('admin.posts.edit', $post) .'" class="alert-link">View</a>' .
            '</span>';
    }
}
