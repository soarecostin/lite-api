<?php

namespace App\Observers;

use App\Enums\SubscriberState;
use App\Subscriber;
use Illuminate\Support\Carbon;

class SubscriberObserver
{
    /**
     * Handle the subscriber "saving" event.
     *
     * @param  \App\Subscriber  $subscriber
     * @return void
     */
    public function saving(Subscriber $subscriber)
    {
        // Check if the state has changed
        if ($subscriber->isDirty('state')) {
            if ($subscriber->state->is(SubscriberState::Active())) {
                $subscriber->date_subscribe = Carbon::now();
            }
            if ($subscriber->state->is(SubscriberState::Unsubscribed())) {
                $subscriber->date_unsubscribe = Carbon::now();
            }
        }
    }
}
