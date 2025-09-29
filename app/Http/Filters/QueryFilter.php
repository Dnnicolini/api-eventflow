<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class QueryFilter
{
    protected $builder;
    protected $request;
    protected array $searchable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder, array $searchable = [])
    {
        $this->builder = $builder;
        $this->searchable = $searchable;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key) && filled($value)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    public function search($term)
    {
        $term = trim($term);

        $columns = $this->searchable;
        if (empty($columns)) {
            $model = $this->builder->getModel();
            $table = $model->getTable();
            $columns = Schema::getColumnListing($table);
        }

        $this->builder->where(function ($query) use ($columns, $term) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $term . '%');
            }
        });
    }
}
