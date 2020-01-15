<?php

namespace App\Http\Requests;

use App\Enums\SubscriberState;
use Illuminate\Validation\Rule;

class UpdateSubscriber extends StoreSubscriber
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return collect(parent::rules())
                ->forget('email')
                ->merge([
                    'state' => [
                        'sometimes',
                        Rule::in([
                            SubscriberState::Active(),
                            SubscriberState::Unsubscribed(),
                        ]),
                    ],
                ])->all();
    }
}
