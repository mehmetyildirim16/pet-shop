<?php

namespace App\Models\Orders;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasUuid;

    protected $table = 'order_statuses';
    protected $guarded = [];

}
