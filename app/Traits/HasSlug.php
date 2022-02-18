<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {

        static::creating(function ($model) {
            $model->slug = Str::slug($model->attributes['title']);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->attributes['title']);
        });
    }
}
