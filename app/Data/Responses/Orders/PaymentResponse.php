<?php

namespace App\Data\Responses\Orders;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Orders\Payment;
use Carbon\Carbon;

class PaymentResponse extends BaseJsonResponse
{

    public function toArray(): array
    {
        assert($this->model instanceof Payment);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Type' => $this->model->type,
            'Details' => $this->model->details,
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
