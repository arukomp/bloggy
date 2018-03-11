<?php

use Faker\Generator as Faker;
use Arukomp\Bloggy\Models\PostType;

$factory->define(PostType::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'plural' => $faker->words(2, true),
        'description' => $faker->sentence,
    ];
});
