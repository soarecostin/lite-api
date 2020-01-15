<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\SubscriberState;
use App\Subscriber;
use App\SubscriberField;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Subscriber::class, function (Faker $faker) {
    // temporarily disable events (we need to manually set the date_subscribe and date_unsubscribe)
    Subscriber::unsetEventDispatcher();

    $state = collect([
        SubscriberState::Active(),
        SubscriberState::Unsubscribed(),
        SubscriberState::Unconfirmed(),
    ])->random();

    $dateSubscribed = Carbon::now()->subMinutes(mt_rand(1, 60 * 24 * 30));

    return [
        'user_id' => auth()->user()->id ?? factory(App\User::class),
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'state' => $state->value,
        'date_subscribe' => $dateSubscribed,
        'date_unsubscribe' => $state->is(SubscriberState::Unsubscribed())
            ? (clone $dateSubscribed)->addMinutes(mt_rand(1, 60 * 24))
            : null,
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
