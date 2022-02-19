<?php

namespace App\Data\Responses\Products;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Products\Product;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ProductResponse extends BaseJsonResponse
{

    public function __construct(
         Product $product,
    ) {
        parent::__construct($product);
    }

    public function toArray(): array
    {
        assert($this->model instanceof Product);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Category Name' => $this->model->category->title,
            'Brand Name' => $this->model->brand?->title,
            'Title' => $this->model->title,
            'Discription' => $this->model->description,
            'Image' => $this->model->metadata['image'] ?? '',
            'Price' => $this->model->price,
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
