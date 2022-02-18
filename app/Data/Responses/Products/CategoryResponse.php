<?php

namespace App\Data\Responses\Products;

use App\Models\Products\Category;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CategoryResponse
{

    public function __construct(
        public Category $category,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->category->id,
            'uuid' => $this->category->uuid,
            'Title' => $this->category->title,
            'Slug' => $this->category->slug,
            'Created At' => Carbon::parse($this->category->created_at)->format('d.m.Y'),
        ];
    }

    public static function jsonSerialize(Collection $categories): JsonResponse
    {
        $categories = $categories->map(fn(Category $category) => new CategoryResponse($category));

        return response()->json($categories->toArray());
    }
}
