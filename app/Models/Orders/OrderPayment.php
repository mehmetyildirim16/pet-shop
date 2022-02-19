<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';
    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
    ];
}
