<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Arukomp\Bloggy\Models\PostType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function itShowsAllPostTypesInTheMenu()
    {
        $postTypes = factory(PostType::class, 2)->create();

        $this->get(route('admin.dashboard'))
            ->assertStatus(200)
            ->assertSeeText(ucfirst($postTypes[0]->plural))
            ->assertSee(route('admin.postType.posts.index', $postTypes[0]))
            ->assertSeeText(ucfirst($postTypes[1]->plural))
            ->assertSee(route('admin.postType.posts.index', $postTypes[1]));
    }
}
