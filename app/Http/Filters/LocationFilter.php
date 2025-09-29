<?php

namespace App\Http\Filters;

class LocationFilter extends QueryFilter
{
    public function latitude($value): void
    {
        $this->builder->where('latitude', (float) $value);
    }

    public function longitude($value): void
    {
        $this->builder->where('longitude', (float) $value);
    }
}
