<?php

namespace App\Models\Products;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Products\Category
 *
 * @property int                             $id
 * @property string                          $uuid
 * @property string                          $title
 * @property string                          $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUuid($value)
 * @mixin \Eloquent
 */
class Category extends Model
{

    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->attributes['title']);
            $model->uuid = Str::uuid();
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->attributes['title']);
        });
    }

}
