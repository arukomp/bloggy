<?php

namespace Arukomp\Bloggy\Tests\Feature;

use Arukomp\Bloggy\Tests\TestCase;
use Arukomp\Bloggy\Models\PostType;
use Arukomp\Bloggy\Tests\Stubs\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTypesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->actingAs($this->user);
    }

    /** @test */
    public function itCanViewAllPostTypes()
    {
        factory(PostType::class, 2)->create();
        $postTypes = PostType::all();

        $this->get(route('admin.postTypes.index'))
            ->assertViewIs('bloggy::admin.postTypes.index')
            ->assertViewHas('postTypes', $postTypes)
            ->assertSeeText($postTypes[0]->name)
            ->assertSeeText($postTypes[1]->name);
    }

    /** @test */
    public function itCanCreatePostTypes()
    {
        $this->get(route('admin.postTypes.create'))
            ->assertViewIs('bloggy::admin.postTypes.create')
            ->assertSeeText('Add a new Post Type');
    }

    /** @test */
    public function itCanStoreANewPostType()
    {
        $postType = factory(PostType::class)->make();

        $this->post(route('admin.postTypes.store'), [
            'name' => $postType->name,
            'plural' => $postType->plural,
            'slug' => str_slug($postType->name),
            'description' => $postType->description,
        ]);

        $this->assertDatabaseHas('post_types', [
            'name' => $postType->name,
            'plural' => $postType->plural,
            'description' => $postType->description,
            'slug' => str_slug($postType->name, '-'),
            'created_by' => $this->user->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function itCannotStorePostTypeWithInvalidData()
    {
        $this->post(route('admin.postTypes.store'), [
                'name' => '',
                'plural' => '',
                'slug' => ''
            ])->assertSessionHasErrors([
                'name' => 'Name is required',
                'plural' => 'Plural form is required',
                'slug' => 'URL slug is required'
            ]);

        $postType = factory(PostType::class)->create();

        $this->post(route('admin.postTypes.store'), [
                'name' => 'hello',
                'slug' => $postType->slug
            ])->assertSessionHasErrors([
                'slug' => $postType->slug . ' slug is already in use by a different post type'
            ]);
    }

    /** @test */
    public function itCanEditAPostType()
    {
        $postType = factory(PostType::class)->create()->first();

        $this->get(route('admin.postTypes.edit', $postType))
            ->assertViewIs('bloggy::admin.postTypes.edit')
            ->assertViewHas('postType', $postType);
    }

    /** @test */
    public function itCanEditSoftDeletedPostTypes()
    {
        factory(PostType::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $postType = PostType::withTrashed()->first();

        $this->get(route('admin.postTypes.edit', $postType))
            ->assertViewIs('bloggy::admin.postTypes.edit')
            ->assertViewHas('postType', $postType);
    }

    /** @test */
    public function itCanUpdateAnExistingPostType()
    {
        $postType = factory(PostType::class)->create();

        $this->put(route('admin.postTypes.update', $postType), [
            'name' => 'hello',
            'plural' => 'hellos',
            'slug' => 'test',
        ]);

        $this->assertDatabaseHas('post_types', [
            'id' => $postType->id,
            'name' => 'hello',
            'plural' => 'hellos',
            'slug' => 'test'
        ]);
    }

    /** @test */
    public function itCannotUpdateWithInvalidData()
    {
        $postType = factory(PostType::class)->create();

        $this->put(route('admin.postTypes.update', $postType), [
                'name' => '',
                'plural' => '',
                'slug' => ''
            ])->assertSessionHasErrors([
                'name' => 'Name is required',
                'plural' => 'Plural form is required',
                'slug' => 'URL slug is required'
            ]);

        $anotherPostType = factory(PostType::class)->create();

        $this->put(route('admin.postTypes.update', $postType), [
                'name' => $anotherPostType->name,
                'slug' => $anotherPostType->slug
            ])->assertSessionHasErrors([
                'slug' => $anotherPostType->slug . ' slug is already in use by a different post type'
            ]);
    }

    /** @test */
    public function itShouldNotThrowErrorsWhenUpdatingSameProperties()
    {
        $postType = factory(PostType::class)->create();

        $this->put(route('admin.postTypes.update', $postType), [
                'name' => $postType->name,
                'plural' => $postType->plural,
                'slug' => $postType->slug
            ])->assertSessionMissing('erorrs');
    }

    /** @test */
    public function itCanSoftDeleteAPostType()
    {
        $postType = factory(PostType::class)->create();

        $this->delete(route('admin.postTypes.destroy', $postType));

        $this->assertSoftDeleted('post_types', ['id' => $postType->id]);
    }

    /** @test */
    public function itCanRestoreDeletedPostTypes()
    {
        $initialCount = PostType::count();
        $postType = tap(factory(PostType::class)->create())->delete();

        $this->assertEquals($initialCount, PostType::count());

        $this->get(route('admin.postTypes.restore', $postType));

        $this->assertEquals($initialCount + 1, PostType::count());
    }
}
