<?php

namespace App\Data\Responses\Products;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Products\Brand;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BrandResponse extends BaseJsonResponse
{
    public function toArray(): array
    {
        assert($this->model instanceof Brand);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Title' => $this->model->title,
            'Slug' => $this->model->slug,
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
