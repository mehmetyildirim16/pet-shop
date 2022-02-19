<?php

namespace App\Data\Responses\Orders;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Orders\Order;
use Carbon\Carbon;

class OrderResponse extends BaseJsonResponse
{
    public function toArray(): array
    {
        assert($this->model instanceof Order);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Status' => $this->model->orderStatus->title,
            'Payment' => $this->model->payment ? response()->json((new PaymentResponse($this->model->payment))->toArray()): null,
            'Products' => $this->model->products,
            'Address' => $this->model->address,
            'Delivery Fee' => $this->model->delivery_fee . '€',
            'Total Price (Delivery fee exc.)' => $this->model->total_price . '€',
            'Shipped At' => Carbon::parse($this->model->shipped_at)->format('d/m/Y H:i'),
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
