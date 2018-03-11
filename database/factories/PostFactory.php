<?php

use Faker\Generator as Faker;
use App\Models\Post;
use App\User;
use Arukomp\Bloggy\Models\PostType;

$factory->define(Post::class, function (Faker $faker) {
    $user = User::first();
    $postType = PostType::first() ?? factory(PostType::class)->create();

    return [
        'title' => $faker->sentence,
        'body' => $faker->text,
        'allow_comments' => true,
        'author_id' => optional($user)->id,
        'updated_by' => optional($user)->id,
        'post_type_id' => optional($postType)->id,
    ];
});
