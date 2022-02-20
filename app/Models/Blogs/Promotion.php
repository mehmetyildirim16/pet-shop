<?php

namespace App\Models\Blogs;

use App\Traits\HasFile;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasUuid;
    use HasFile;
    use HasFactory;

    protected $guarded = [];
    protected $table = 'promotions';

    protected $casts = [
        'metadata' => 'array',
    ];

}
