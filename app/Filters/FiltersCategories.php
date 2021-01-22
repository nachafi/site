<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersCategories implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('categories', function ($query) use ($value) {
            if (is_array($value)) {
                return $query->whereIn('category_id', $value);
            }

            return $query->where('category_id', $value);
        });
    }
}
