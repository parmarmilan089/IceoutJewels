<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class QueryHelper
{
    private $defaultKeys = [
        "page",
        "perPage",
        "_order",
        "_sort",
        "ids",
        "q"
    ];

    function __construct(private Request $request, private Builder $model)
    {
    }

    private function map_operator(string $operator)
    {
        switch ($operator) {
            case 'ne':
                return '!=';
            case 'gte':
                return '>=';
            case 'lte':
                return '<=';
            case 'gt':
                return '>';
            case 'lt':
                return '<';
            case 'like':
                return 'like';
            case 'eq':
            default:
                return '=';
        }
    }
    private function filter()
    {
        $filters = $this->request->except($this->defaultKeys);
        $this->applyFilter($this->model, $filters);
        return $this;
    }

    private function applyFilter($query, $filters)
    {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                if (Arr::isList($value)) {
                    $query->whereIn($key, $value);
                } else {
                    $query->whereHas($key, function ($query) use ($key, $value) {
                        $this->applyFilter($query, $value);
                    });
                }
                continue;
            }
            $this->applyFieldFilter($query, $key, $value);
        }
        return $this;
    }

    private function applyFieldFilter($query, $key, $value)
    {
        $parts = explode("_", $key);
        $operator = count($parts) > 1 ? array_pop($parts) : "";
        $field = implode("_", $parts);
        $comparator = $this->map_operator($operator ?? "");
        $field .= $comparator === "=" && $operator !== "eq" && $operator ? "_$operator" : "";
        $fields = explode("|", $field);
        if (count($fields) > 1) {
            $query->where(function ($q) use ($fields, $comparator, $value) {
                foreach ($fields as $f) {
                    if ($comparator === "like") {
                        $q->orWhere($f, "like", "%$value%");
                    } else {
                        $q->orWhere($f, $comparator, $value);
                    }
                }
            });
        } else {
            if ($comparator === "like") {
                $query->where($field, "like", "%$value%");
            } else {
                $query->where($field, $comparator, $value);
            }
        }

    }

    private function sort()
    {
        $sort = $this->request->get('_sort');
        $order = $this->request->get('_order');
        if (empty($sort)) {
            return $this;
        }

        $sortables = explode(',', $sort);
        $sortableOrders = explode(',', $order ?? "");

        foreach ($sortables as $key => $field) {
            $dir = isset($sortableOrders[$key]) && !empty($sortableOrders[$key]) ? $sortableOrders[$key] : "desc";
            $this->model->orderBy($field, $dir);
        }
        return $this;
    }

    private function paginate()
    {

        $page = $this->request->get("page");
        $perPage = $this->request->get("perPage", 10);
        if($perPage == 'all'){
            $perPage = $this->model->count();
        }
        $result = $this->model->paginate($perPage, ["*"], 'page', $page);
        return [
            'meta' => [
                'last_page' => $result->lastPage(),
                'per_page'  => $result->perPage(),
                'total'     => $result->total(),
            ],
            'data' => $result->items()
        ];
    }

    public function query()
    {
        return $this->filter()->sort()->paginate();
    }
}
