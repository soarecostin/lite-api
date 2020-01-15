<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

// GET /subscribers?filter[search]=foo
class SearchSubscribersFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where('name', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%${value}%");
    }
}