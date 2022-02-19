<?php

namespace App\Models\Orders;


use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    use HasUuid;

    protected $table = 'orders';
    protected $guarded = [];

    protected $casts = [
        'products' => 'array',
        'address' => 'array',
    ];

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
        return $this->belongsTo(Payment::class);
    }


}
