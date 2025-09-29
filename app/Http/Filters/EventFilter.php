<?php

namespace App\Http\Filters;

use Illuminate\Support\Collection;

class EventFilter extends QueryFilter
{
    public function category($value): void
    {
        $values = $this->normaliseToArray($value);

        $ids = $values->filter(fn ($item) => ctype_digit($item))->map('intval');
        $slugs = $values->reject(fn ($item) => ctype_digit($item));

        $this->builder->where(function ($query) use ($ids, $slugs) {
            if ($ids->isNotEmpty()) {
                $query->whereIn('category_id', $ids->all());
            }

            if ($slugs->isNotEmpty()) {
                $query->orWhereHas('category', function ($relation) use ($slugs) {
                    $relation->whereIn('slug', $slugs->all());
                });
            }
        });
    }

    public function location($value): void
    {
        $values = $this->normaliseToArray($value);

        $ids = $values->filter(fn ($item) => ctype_digit($item))->map('intval');
        $names = $values->reject(fn ($item) => ctype_digit($item));

        $this->builder->where(function ($query) use ($ids, $names) {
            if ($ids->isNotEmpty()) {
                $query->whereIn('location_id', $ids->all());
            }

            if ($names->isNotEmpty()) {
                $query->orWhereHas('location', function ($relation) use ($names) {
                    $relation->whereIn('name', $names->all());
                });
            }
        });
    }

    public function organizer($value): void
    {
        $values = $this->normaliseToArray($value);

        $ids = $values->filter(fn ($item) => ctype_digit($item))->map('intval');
        $emailsOrNames = $values->reject(fn ($item) => ctype_digit($item));

        $this->builder->where(function ($query) use ($ids, $emailsOrNames) {
            if ($ids->isNotEmpty()) {
                $query->whereIn('user_id', $ids->all());
            }

            if ($emailsOrNames->isNotEmpty()) {
                $query->orWhereHas('organizer', function ($relation) use ($emailsOrNames) {
                    $relation->where(function ($organizerQuery) use ($emailsOrNames) {
                        $organizerQuery->whereIn('email', $emailsOrNames->all())
                            ->orWhereIn('name', $emailsOrNames->all());
                    });
                });
            }
        });
    }

    public function from_date(string $value): void
    {
        $this->builder->whereDate('start_date', '>=', $value);
    }

    public function to_date(string $value): void
    {
        $this->builder->whereDate('start_date', '<=', $value);
    }

    public function price_min($value): void
    {
        $this->builder->where('price', '>=', (float) $value);
    }

    public function price_max($value): void
    {
        $this->builder->where('price', '<=', (float) $value);
    }

    protected function normaliseToArray($value): Collection
    {
        $items = is_array($value) ? $value : explode(',', (string) $value);

        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter();
    }
}
