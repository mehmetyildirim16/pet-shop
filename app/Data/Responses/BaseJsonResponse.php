<?php

namespace App\Data\Responses;

use App\Utils\PaginatorHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class BaseJsonResponse
{

    final public function __construct(public Model $model) { }

    public static function jsonSerialize(Collection $models, ?int $page = 1): JsonResponse
    {
        $paginator = new PaginatorHelper();
        $models_paginated = $paginator->paginate($models, 10, $page, []);
        $paginatorInfo = [
            'total' => $models_paginated->total(),
            'per_page' => $models_paginated->perPage(),
            'page' => $models_paginated->currentPage(),
            'last_page' => $models_paginated->lastPage(),
        ];
        $models_array = $models_paginated->map(fn(Model $model) => (new static($model))->toArray());
        return response()->json($models_array->merge($paginatorInfo));
    }

    abstract public function toArray(): array;
}
