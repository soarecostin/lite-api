<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SubscriberField;
use Faker\Generator as Faker;

$factory->define(SubscriberField::class, function (Faker $faker) {
    return [
        'value' => $faker->word,
    ];
});

$factory->state(SubscriberField::class, 'date', function ($faker) {
    return [
        'value' => $faker->date,
    ];
});

$factory->state(SubscriberField::class, 'number', function ($faker) {
    return [
        'value' => $faker->numberBetween(0, 100),
    ];
});

$factory->state(SubscriberField::class, 'text', function ($faker) {
    return [
        'value' => $faker->word,
    ];
});

$factory->state(SubscriberField::class, 'boolean', function ($faker) {
    return [
        'value' => $faker->boolean,
    ];
});
