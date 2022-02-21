<?php

namespace App\Data\Responses\Blog;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Blogs\Post;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PostResponse extends BaseJsonResponse
{

    public function toArray(): array
    {
        assert($this->model instanceof Post);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Title' => $this->model->title,
            'Content' => $this->model->content,
            'Author' => $this->model->metadata['author'],
            'Image' => Storage::disk('public')->url('/pet-shop/'.File::whereUuid($this->model->metadata['image'])->firstOrFail()->path),
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
