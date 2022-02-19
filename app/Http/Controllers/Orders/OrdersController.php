<?php

namespace App\Http\Controllers\Orders;

use App\Actions\OrderAction;
use App\Data\Responses\Orders\OrderResponse;
use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function __construct(public OrderAction $action) { }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function createOrder(Request $request): JsonResponse
    {
        $user = authUser($request);
        $order = $this->action->create($request->all(), $user);
        $response = new OrderResponse($order);
        return response()->json($response->toArray(), 201);
    }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function getOrders(Request $request): JsonResponse
    {
        $user = authUser($request);
        $orders = Order::where('user_id', $user->id)->get();
        return response()->json(OrderResponse::jsonSerialize($orders), 200);
    }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function getOrder(string $uuid, Request $request): JsonResponse
    {
        $user = authUser($request);
        $order = Order::where('user_id', $user->id)->whereUuid($uuid)->firstOrFail();
        return response()->json((new OrderResponse($order))->toArray(), 200);
    }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function updateOrder(string $uuid, Request $request): JsonResponse
    {
        $user = authUser($request);
        $order = Order::where('user_id', $user->id)->whereUuid($uuid)->firstOrFail();
        $order = $this->action->update($order, $request->all());
        return response()->json((new OrderResponse($order))->toArray(), 200);
    }

    public function deleteOrder(string $uuid, Request $request):JsonResponse
    {
        $user = authUser($request);
        $order = Order::where('user_id', $user->id)->whereUuid($uuid)->firstOrFail();
        $order->delete();
        return response()->json('Order deleted', 200);
    }
}
