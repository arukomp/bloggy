<?php

use Arukomp\Bloggy\Models\Post;
use Arukomp\Bloggy\Models\PostType;
use Arukomp\Bloggy\Tests\Stubs\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $user = User::first();
    $postType = PostType::first() ?? factory(PostType::class)->create();

    return [
        'title'          => $faker->sentence,
        'body'           => $faker->text,
        'allow_comments' => true,
        'author_id'      => optional($user)->id,
        'updated_by'     => optional($user)->id,
        'post_type_id'   => optional($postType)->id,
    ];
});
