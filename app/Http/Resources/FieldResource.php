<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
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
            'title' => $this->title,
            'key' => $this->key,
            'type' => $this->type->key,
            'date_updated' => $this->updated_at->toDateTimeString(),
            'date_created' => $this->created_at->toDateTimeString(),
        ];
    }
}
