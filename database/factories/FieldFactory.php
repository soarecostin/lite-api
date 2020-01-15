<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Field;
use App\Enums\FieldType;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Field::class, function (Faker $faker) {
    $title = $faker->words(mt_rand(1,2), true);
    return [
        'user_id' => auth()->user()->id ?? factory(App\User::class),
        'title' => Str::title($title),
        'key' => Str::snake($title),
        'type' => FieldType::getRandomValue(),
    ];
});
