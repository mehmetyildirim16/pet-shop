<?php

namespace App\Data\Responses\Products;

use App\Models\Products\Brand;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BrandResponse
{

    public function __construct(
        public Brand $brand,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->brand->id,
            'uuid' => $this->brand->uuid,
            'Title' => $this->brand->title,
            'Slug' => $this->brand->slug,
            'Created At' => Carbon::parse($this->brand->created_at)->format('d.m.Y'),
        ];
    }

    public static function jsonSerialize(Collection $brands): JsonResponse
    {
        $brands = $brands->map(fn(Brand $brand) => new BrandResponse($brand));

        return response()->json($brands->toArray());
    }
}
