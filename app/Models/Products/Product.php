<?php

namespace App\Models\Products;


use App\Traits\HasFile;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Products\Product
 *
 * @property int $id
 * @property string $category_uuid
 * @property string $uuid
 * @property string $title
 * @property string $description
 * @property array $metadata
 * @property string $price
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products\Category $category
 * @property-read \App\Models\Products\Brand|null $brand
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUuid($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFile;
    use HasUuid;
    use HasFactory;

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $table = 'products';
    protected $guarded = [];

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_uuid' => 'required|string',
            'brand' => 'nullable|string',
            'image' => 'nullable',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function getBrandAttribute():Brand | null
    {
        return isset($this->metadata[0]['brand']) ? Brand::whereUuid($this->metadata[0]['brand'])->first() : new Brand();
    }

}
