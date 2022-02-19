<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderPayment extends Model
{
    protected $table = 'order_payments';
    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
    ];

    public function order():HasOne
    {
        return $this->hasOne(Order::class);
    }
}
