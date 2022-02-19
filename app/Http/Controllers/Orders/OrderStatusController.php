<?php

namespace App\Http\Controllers\Orders;

use App\Data\Responses\Orders\OrderStatusResponse;
use App\Http\Controllers\Controller;
use App\Models\Orders\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{

    public function getOrderStatuses(): JsonResponse
    {
        $statuses = OrderStatus::all();
        return OrderStatusResponse::jsonSerialize($statuses);
    }

    public function getOrderStatus(string $uuid): JsonResponse
    {
        $status = OrderStatus::whereUuid($uuid)->firstOrFail();
        return response()->json((new OrderStatusResponse($status))->toArray());
    }

    public function createOrderStatus(Request $request): JsonResponse
    {
        $status = OrderStatus::create($request->all());
        return response()->json((new OrderStatusResponse($status))->toArray());
    }

    public function updateOrderStatus(Request $request, string $uuid): JsonResponse
    {
        $status = OrderStatus::whereUuid($uuid)->firstOrFail();
        $status->update($request->all());
        return response()->json((new OrderStatusResponse($status))->toArray());
    }

    public function deleteOrderStatus(string $uuid): JsonResponse
    {
        $status = OrderStatus::whereUuid($uuid)->firstOrFail();
        $status->delete();
        return response()->json('status deleted');
    }

}
