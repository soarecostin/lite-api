<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'state' => $this->state->key,
            'fields' => SubscriberFieldResource::collection($this->fields),
            'date_subscribe' => optional($this->date_subscribe)->toDateString(),
            'date_unsubscribe' => optional($this->date_unsubscribe)->toDateString(),
            'date_updated' => $this->updated_at->toDateTimeString(),
            'date_created' => $this->created_at->toDateTimeString(),
        ];
    }
}
