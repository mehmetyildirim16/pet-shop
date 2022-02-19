<?php

namespace App\Data\Responses\Orders;

use App\Data\Responses\BaseJsonResponse;
use App\Models\Orders\OrderStatus;
use Carbon\Carbon;

class OrderStatusResponse extends BaseJsonResponse
{

    public function toArray(): array
    {
        assert($this->model instanceof OrderStatus);
        return [
            'id' => $this->model->id,
            'uuid' => $this->model->uuid,
            'Title' => $this->model->title,
            'Created At' => Carbon::parse($this->model->created_at)->format('d.m.Y'),
        ];
    }
}
