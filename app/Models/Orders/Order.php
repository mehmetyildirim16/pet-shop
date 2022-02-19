<?php

namespace App\Models\Orders;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function OrderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function OrderPayment(): BelongsTo
    {
        return $this->belongsTo(OrderPayment::class);
    }


}
