<?php

namespace App\Data\Responses\Blog;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Blogs\Promotion;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PromotionResponse extends BaseJsonResponse
{

    public function toArray(): array
    {
        assert($this->model instanceof Promotion);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Title' => $this->model->title,
            'Content' => $this->model->content,
            'Valid From' => Carbon::parse($this->model->metadata['valid_from'])->toDateString(),
            'Valid To' => Carbon::parse($this->model->metadata['valid_to'])->toDateString(),
            'Image' => Storage::disk('public')->url('/pet-shop/'.File::whereUuid($this->model->metadata['image'])->firstOrFail()->path),
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
