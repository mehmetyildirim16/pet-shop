<?php

namespace App\Models\Products;


use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFile;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $table = 'products';
    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

}
