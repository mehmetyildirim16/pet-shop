<?php

namespace App\Data\Responses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class BaseJsonResponse
{

    final public function __construct(public Model $model) { }

    public static function jsonSerialize(Collection $models): JsonResponse
    {
        $models_array = $models->map(fn(Model $model) => (new static($model))->toArray());
        return response()->json($models_array);
    }

    abstract public function toArray(): array;
}
