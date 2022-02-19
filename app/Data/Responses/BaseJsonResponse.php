<?php

namespace App\Data\Responses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BaseJsonResponse
{

    public function __construct(public Model $model) { }

    public static function jsonSerialize(Collection $models): JsonResponse
    {
        $models = $models->map(fn(Model $model) => new self($model));

        return response()->json($models->toArray());
    }
}
