<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function postHasUrlAttribute()
    {
        $postType = factory(PostType::class)->create();
        $post = factory(Post::class)->create(['post_type_id' => $postType->id])->first();

        $expected = route('posts.show', [$postType->slug, $post->slug]);
        $this->assertEquals($expected, $post->url);
    }

    /** @test */
    public function itCanSeeAPost()
    {
        $post = factory(Post::class)->create(['active' => true])->first();

        $response = $this->get($post->url)
            ->assertStatus(200)
            ->assertViewIs('posts.show')
            ->assertViewHas('post', $post)
            ->assertSeeText($post->title)
            ->assertSee($post->body)
            ->assertSee($post->author->name);
    }

    /** @test */
    public function itCanSeeEditLinkWhenLoggedIn()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create()->first();

        $this->get($post->url)
            ->assertDontSeeText('Edit Post')
            ->assertDontSee(route('admin.posts.edit', $post));

        $this->actingAs($user)->get($post->url)
            ->assertSeeText('Edit Post')
            ->assertSee(route('admin.posts.edit', $post));
    }

    /** @test */
    public function itCannotSeePostIfNotActive()
    {
        $post = factory(Post::class)->create(['active' => false]);

        $this->get($post->url)
            ->assertStatus(404);
    }

    /** @test */
    public function itCanSeeUnpublishedPostWhenLoggedIn()
    {
        $post = factory(Post::class)->create(['active' => false])->first();

        $this->actingAs($this->user)->get($post->url)
            ->assertStatus(200)
            ->assertSeeText($post->title)
            ->assertSeeText('Draft (Not Published)');
    }

    /** @test */
    public function itCanSeeTrashedPostWhenLoggedIn()
    {
        $post = tap(factory(Post::class)->create())->delete();

        $this->actingAs($this->user)->get($post->url)
            ->assertStatus(200)
            ->assertSeeText($post->title)
            ->assertSeeText('Trashed');
    }

    /** @test */
    public function itCanAccessPostSlugInUrl()
    {
        $post = factory(Post::class)->create([
            'title' => 'Hello World',
            'active' => true
            ])->first();

        $this->get('/' . $post->type->slug . '/hello-world')
            ->assertStatus(200)
            ->assertViewIs('posts.show')
            ->assertViewHas('post', $post);
    }

    /** @test */
    public function itRedirectsToPostSlugUrl()
    {
        $anotherPostType = factory(PostType::class)->create();
        $post = factory(Post::class)->create([
            'active' => true,
            'post_type_id' => $anotherPostType->id
        ]);

        $this->get('/post/' . $post->id)
            ->assertRedirect('/' . $anotherPostType->slug . '/' . $post->slug);
    }

    /** @test */
    public function itRedirectsFromPostTypeIdToSlug()
    {
        $post = factory(Post::class)->create(['active' => true])->first();

        $this->get('/' . $post->type->id . '/' . $post->slug)
            ->assertRedirect($post->url);
    }

    /** @test */
    public function itHandlesNonExistentPostTypes()
    {
        $this->get('/asdf/asdf')
            ->assertStatus(404);
    }
}
