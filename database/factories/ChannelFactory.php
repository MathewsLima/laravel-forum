<?php

use App\Channel;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {
    $word = $faker->word;
    return [
        'name' => $word,
        'slug' => $word
    ];
});
