<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\FieldType;
use App\Field;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Field::class, function (Faker $faker) {
    $title = $faker->words(mt_rand(1, 2), true);

    return [
        'user_id' => auth()->user()->id ?? factory(App\User::class),
        'title' => Str::title($title),
        'key' => Str::snake($title),
        'type' => FieldType::getRandomValue(),
    ];
});
