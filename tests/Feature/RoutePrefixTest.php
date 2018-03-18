<?php

namespace Arukomp\Bloggy\Tests\Feature;

use Arukomp\Bloggy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Tests\Stubs\User;
use Arukomp\Bloggy\Models\PostType;

class PostsControllerTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('bloggy.routes_prefix', 'test_prefix');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->actingAs(factory(User::class)->create());
    }

    /** @test */
    public function itPrefixesAdminRoutes()
    {
        $this->assertEquals(
            config('app.url').'/test_prefix/admin/dashboard',
            route('admin.dashboard')
        );

        $this->assertEquals(
            config('app.url').'/test_prefix/admin/postTypes',
            route('admin.postTypes.index')
        );
    }

    /** @test */
    public function itPrefixesPublicRoutes()
    {
        $post = factory(Post::class)->create([]);

        $this->assertEquals(
            config('app.url').'/test_prefix/'.$post->type->slug . '/' . $post->slug,
            $post->url
        );

        $this->withoutExceptionHandling()
            ->get($post->url)
            ->assertStatus(200);
    }
}
