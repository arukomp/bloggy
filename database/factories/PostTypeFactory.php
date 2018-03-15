<?php

use Arukomp\Bloggy\Models\PostType;
use Faker\Generator as Faker;

$factory->define(PostType::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(2, true),
        'plural'      => $faker->words(2, true),
        'description' => $faker->sentence,
    ];
});
