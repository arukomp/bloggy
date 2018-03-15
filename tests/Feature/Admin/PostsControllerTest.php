<?php

namespace Arukomp\Bloggy\Tests\Feature\Admin;

use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;
use Arukomp\Bloggy\Tests\Stubs\User;
use Arukomp\Bloggy\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

class PostsControllerTest extends TestCase
{
    use WithFaker;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user)
            ->followingRedirects();

        $this->postType = PostType::first() ?? factory(PostType::class)->create();
    }

    /** @test */
    public function itCanSeeAListOfPosts()
    {
        $posts = factory(Post::class, 2)->create();
        $trashedPost = factory(Post::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->withoutExceptionHandling()
            ->get(route('admin.postType.posts.index', $this->postType))
            ->assertStatus(200)
            ->assertSeeText($posts[0]->title)
            ->assertSeeText($posts[1]->title)
            ->assertDontSeeText($trashedPost->title)
            ->assertSeeText($this->postType->plural)
            ->assertSeeText('All '.$this->postType->plural.' '.$posts->count())
            ->assertSeeText('Trashed 1');
    }

    /** @test */
    public function itCanSeeAListOfTrashedPosts()
    {
        $posts = factory(Post::class, 2)->create(['deleted_at' => \Carbon\Carbon::now()]);
        $normalPost = factory(Post::class)->create();

        $this->get(route('admin.postType.trashedPosts.index', $this->postType))
            ->assertStatus(200)
            ->assertSeeText($posts[0]->title)
            ->assertSeeText($posts[1]->title)
            ->assertDontSeeText($normalPost->title)
            ->assertSeeText('Deleted '.$this->postType->plural)
            ->assertSeeText('All '.$this->postType->plural.' 1')
            ->assertSeeText('Trashed '.$posts->count());
    }

    /** @test */
    public function itCannotSeePostsFromOtherPostTypes()
    {
        $anotherPostType = factory(PostType::class)->create();
        $anotherPost = factory(Post::class)->create(['post_type_id' => $anotherPostType->id]);

        $this->withoutExceptionHandling()
            ->get(route('admin.postType.posts.index', $this->postType))
            ->assertStatus(200)
            ->assertDontSeeText($anotherPost->title)
            ->assertDontSeeText($anotherPostType->name);
    }

    /** @test */
    public function itCanCreateANewPost()
    {
        $this->get(route('admin.postType.posts.create', $this->postType))
            ->assertViewIs('bloggy::admin.posts.create')
            ->assertSeeText('Add a new '.$this->postType->name);
    }

    /** @test */
    public function itCanStoreANewPostAsDraft()
    {
        $samplePostData = factory(Post::class)->make();

        $response = $this->post(route('admin.postType.posts.store', $this->postType), [
            'title'          => $samplePostData->title,
            'body'           => $samplePostData->body,
            'allow_comments' => $samplePostData->allow_comments,
            'saveAsDraft'    => 'Submit',
        ]);

        $this->assertDatabaseHas('posts', [
            'title'          => $samplePostData->title,
            'body'           => $samplePostData->body,
            'allow_comments' => 1,
            'excerpt'        => str_limit($samplePostData->body, 350),
            'slug'           => str_slug($samplePostData->title, '-'),
            'active'         => 0,
            'post_type_id'   => $this->postType->id,
        ]);

        $post = Post::first();

        $response->assertViewIs('bloggy::admin.posts.edit')
            ->assertViewHas('post', $post);
    }

    /** @test */
    public function itCanStoreANewPostAsPublished()
    {
        $samplePostData = factory(Post::class)->make();

        $response = $this->post(route('admin.postType.posts.store', $this->postType), [
            'title'          => $samplePostData->title,
            'body'           => $samplePostData->body,
            'allow_comments' => $samplePostData->allow_comments,
            'publish'        => 'Submit',
        ]);

        $this->assertDatabaseHas('posts', [
            'title'          => $samplePostData->title,
            'body'           => $samplePostData->body,
            'allow_comments' => 1,
            'excerpt'        => str_limit($samplePostData->body, 350),
            'slug'           => str_slug($samplePostData->title, '-'),
            'active'         => 1,
            'post_type_id'   => $this->postType->id,
        ]);

        $post = Post::first();

        $response->assertViewIs('bloggy::admin.posts.edit')
            ->assertViewHas('post', $post);
    }

    /** @test */
    public function itCanPublishThePost()
    {
        $post = factory(Post::class)->create()->first();
        $this->assertFalse($post->active);

        $this->get(route('admin.posts.publish', $post));

        $this->assertDatabaseHas('posts', [
            'id'     => $post->id,
            'active' => true,
        ]);
    }

    /** @test */
    public function itCanUnpublishThePost()
    {
        $post = factory(Post::class)->create(['active' => true])->first();
        $this->assertTrue($post->active);

        $this->get(route('admin.posts.unpublish', $post));

        $this->assertDatabaseHas('posts', [
            'id'     => $post->id,
            'active' => false,
        ]);
    }

    /** @test */
    public function itCanEditPost()
    {
        $post = factory(Post::class)->create()->first();

        $this->get(route('admin.posts.edit', $post))
            ->assertViewIs('bloggy::admin.posts.edit')
            ->assertViewHas('post', $post)
            ->assertSeeText('Edit '.$this->postType->name.': '.str_limit($post->title, 45))
            ->assertSeeText('Preview')
            ->assertSee(route('posts.show', [$post->type->slug, $post->slug]))
            ->assertSeeText($post->slug);
    }

    /** @test */
    public function itCanUpdatePost()
    {
        $post = factory(Post::class)->create()->first();
        $newTitle = $this->faker->sentence;
        $newBody = $this->faker->text.$this->faker->text;

        $this->put(route('admin.posts.update', $post), [
            'title'          => $newTitle,
            'body'           => $newBody,
            'allow_comments' => false,
        ])->assertViewIs('bloggy::admin.posts.edit');

        $this->assertDatabaseHas('posts', [
            'id'             => $post->id,
            'title'          => $newTitle,
            'body'           => $newBody,
            'excerpt'        => str_limit($newBody, 350),
            'allow_comments' => false,
            'slug'           => str_slug($newTitle, '-'),
        ]);
    }

    /** @test */
    public function itCanUpdatePostsSlugAndExcerpt()
    {
        $post = factory(Post::class)->create()->first();
        $newExcerpt = str_repeat('a', 400);
        $newSlug = 'unusual_slug';
        $newTitle = $this->faker->sentence;
        $newBody = $this->faker->text;

        $this->put(route('admin.posts.update', $post), [
            'excerpt' => $newExcerpt,
            'slug'    => $newSlug,
            'body'    => $newBody,
            'title'   => $newTitle,
        ]);

        $this->assertDatabaseHas('posts', [
            'id'             => $post->id,
            'title'          => $newTitle,
            'body'           => $newBody,
            'slug'           => $newSlug,
            'excerpt'        => $newExcerpt,
            'allow_comments' => $post->allow_comments,
            'active'         => $post->active,
        ]);
    }

    /** @test */
    public function itCanTrashAPost()
    {
        $post = factory(Post::class)->create()->first();

        $this->delete(route('admin.posts.destroy', $post));

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    /** @test */
    public function itCanRestoreATrashedPost()
    {
        $post = factory(Post::class)->create(['deleted_at' => Carbon::now()]);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);

        $this->put(route('admin.posts.restore', $post));

        $this->assertDatabaseHas('posts', [
            'id'         => $post->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function itDoesNotAllowDuplicateSlugs()
    {
        $post = factory(Post::class)->create(['title' => 'Hello World', 'active' => true])->first();

        $response = $this->post(route('admin.postType.posts.store', $this->postType), [
                'title'          => 'Hello World',
                'body'           => 'asdfasdf',
                'allow_comments' => 1,
                'publish'        => 'Submit',
            ])->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'title' => 'Hello World',
            'body'  => 'asdfasdf',
            'slug'  => 'hello-world',
        ]);

        $lastPost = Post::all()->last();

        $this->get(route('admin.posts.edit', $post))
            ->assertSeeText('This slug is shared with these active '.strtolower($this->postType->plural).':')
            ->assertSeeText('Some or all of these '.strtolower($this->postType->plural).' above might not be accessible by the public')
            ->assertSee(route('admin.posts.edit', $lastPost));
    }

    /** @test */
    public function itAllowsDuplicateSlugsOnDifferentPostTypes()
    {
        $first = factory(Post::class)->create(['title' => 'Hello World', 'active' => true]);
        $secondPostType = factory(PostType::class)->create();

        $response = $this->post(route('admin.postType.posts.store', $secondPostType), [
                'title'          => 'Hello World',
                'body'           => 'asdfasdf',
                'allow_comments' => 1,
                'publish'        => 'Submit',
            ])->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'title'        => 'Hello World',
            'body'         => 'asdfasdf',
            'slug'         => 'hello-world',
            'post_type_id' => $secondPostType->id,
        ]);

        $lastPost = Post::all()->last();

        $this->get(route('admin.posts.edit', $first))
            ->assertDontSeeText('This slug is shared with these active '.strtolower($this->postType->plural).':')
            ->assertDontSeeText('Some or all of these '.strtolower($this->postType->plural).' above might not be accessible by the public');
    }

    /** @test */
    public function itShouldNotIncreaseRevisionWhenPublishingAndUnpublishing()
    {
        $post = factory(Post::class)->create(['active' => false]);

        $this->get(route('admin.posts.publish', $post));

        $this->assertDatabaseHas('posts', [
            'id'       => $post->id,
            'revision' => 1,
        ]);

        $this->get(route('admin.posts.unpublish', $post));

        $this->assertDatabaseHas('posts', [
            'id'       => $post->id,
            'revision' => 1,
        ]);
    }

    /** @test */
    public function itShouldNotIncreaseRevisionWhenRestoringAPost()
    {
        $post = factory(Post::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->put(route('admin.posts.restore', $post));

        $this->assertDatabaseHas('posts', [
            'id'       => $post->id,
            'revision' => 1,
        ]);
    }
}
