<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\SubscriberState;
use App\Subscriber;
use App\SubscriberField;
use Faker\Generator as Faker;

$factory->define(Subscriber::class, function (Faker $faker) {
    return [
        'user_id' => auth()->user()->id ?? factory(App\User::class),
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'state' => SubscriberState::getRandomValue(),
        'date_subscribe' => null,
        'date_unsubscribe' => null,
    ];
});

// Empty state in order to trigger the afterCreatingState callback
$factory->state(Subscriber::class, 'with_fields', [])
        ->afterCreatingState(Subscriber::class, 'with_fields', function ($subscriber, $faker) {
            $subscriber->user->fields->each(function ($field) use ($subscriber, $faker) {
                factory(SubscriberField::class)
                    ->states(strtolower($field->type->key))
                    ->create([
                        'subscriber_id' => $subscriber->id,
                        'field_id' => $field->id,
                    ]);
            });
        });
