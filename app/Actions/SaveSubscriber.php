<?php

namespace App\Actions;

use App\Enums\SubscriberState;
use App\Subscriber;
use Illuminate\Http\Request;

class SaveSubscriber
{
    public function execute(Request $request, Subscriber $subscriber)
    {
        $subscriber->fill($this->requestFields($request, $subscriber));
        $subscriber->state = SubscriberState::coerce($request->input('state', 'Active'));
        if (is_null($subscriber->id)) {
            $subscriber->user_id = $request->user()->id;
        }
        $subscriber->save();

        $subscriber->saveFields($request->input('fields'));
    }

    protected function requestFields(Request $request, Subscriber $subscriber)
    {
        return is_null($subscriber->id) 
                ? $request->only(['name', 'email'])
                : $request->only(['name']);
    }
}
