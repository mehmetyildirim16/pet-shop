<?php

namespace App\Models\Blogs;

use App\Traits\HasFile;
use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use HasUuid;
    use HasFile;
    use HasFactory;
    use HasSlug;

    protected $guarded = [];
    protected $table = 'posts';

    protected $casts = [
        'metadata' => 'array',
    ];
}
