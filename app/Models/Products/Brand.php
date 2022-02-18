<?php

namespace App\Models\Products;


use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

    use HasUuid;
    use HasSlug;
    use HasFactory;

    protected $table = 'brands';
    protected $guarded = [];

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
        ];
    }


}
