<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersAttributeValues implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('attribute_values', function ($query) use ($value) {
            if (is_array($value)) {
                return $query->whereIn('value', $value);
            }

            return $query->where('value', $value);
        });
    }
}
