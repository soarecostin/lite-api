<?php

namespace App\Http\Filters;

use App\Enums\SubscriberState;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SubscribersByStateFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $state = SubscriberState::coerce($value);
        return $query->when($state, function ($query) use ($state) {
            return $query->where('state', $state->value);
        });
    }
}