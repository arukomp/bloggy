<?php

namespace Arukomp\Bloggy\Http\ViewComposers\Admin;

use Illuminate\View\View;
use Arukomp\Bloggy\Models\PostType;
use Arukomp\Bloggy\Models\Post;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('menu', $this->getParsedMenu());
    }

    protected function getParsedMenu()
    {
        return array_map(function ($menuItem) {
            if (isset($menuItem['postTypeId'])) {
                $postType = request()->route('postType');
                $post = request()->route('post');
                $post = $post instanceof Post ? $post : Post::withTrashed()->find($post);

                $postTypeId = is_null($postType)
                    ? ($post instanceof Post ? $post->type->id : null)
                    : ($postType instanceof PostType ? $postType->id : intval($postType));

                $menuItem['active'] =
                    ($menuItem['postTypeId'] == $postTypeId)
                    && (!request()->routeIs('admin.postTypes.edit'));
            } elseif (isset($menuItem['activeOn']) && is_array($menuItem['activeOn'])) {
                $menuItem['active'] = request()->routeIs($menuItem['activeOn']);
                // $menuItem['active'] = call_user_func([request(), 'routeIs'], $menuItem['activeOn']);
            }
            return $menuItem;
        }, $this->getMenu());
    }

    protected function getMenu()
    {
        $postTypeLinks = $this->getPostTypeLinks();

        return array_merge([
            [
                'icon' => 'home',
                'name' => 'Dashboard',
                'link' => route('admin.dashboard'),
                'activeOn' => ['admin.dashboard']
            ],
            [
                'type' => 'divider'
            ],
            [
                'icon' => 'file',
                'name' => 'Post Types',
                'link' => route('admin.postTypes.index'),
                'activeOn' => [
                    'admin.postTypes.index',
                    'admin.postTypes.create',
                    'admin.postTypes.edit'
                ]
            ],
            [
                'type' => 'divider'
            ]
        ], $postTypeLinks);
    }

    protected function getPostTypeLinks()
    {
        $postTypes = PostType::all();

        $links = $postTypes->map(function ($type) {
            return [
                'icon' => 'file',
                'name' => ucfirst($type->plural),
                'link' => route('admin.postType.posts.index', $type),
                'postTypeId' => $type->id
            ];
        });

        return $links->toArray();
    }
}
