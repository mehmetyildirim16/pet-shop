<?php

namespace App\Http\Controllers\Orders;

use App\Actions\PaymentAction;
use App\Data\Responses\Orders\OrderStatusResponse;
use App\Data\Responses\Orders\PaymentResponse;
use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Orders\Payment;
use App\Models\Products\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct(public PaymentAction $action) { }

    public function getPayments(Request $request): JsonResponse
    {
        $user = authUser($request);
        $orders = $user->orders;
        $payments = $orders->map(fn($order) => $order->payment)
            ->flatten()
            ->filter(fn($payment) => $payment !== null);
        return PaymentResponse::jsonSerialize($payments, $request->page);
    }

    public function getPayment(Request $request, string $uuid): JsonResponse
    {
        $user = authUser($request);
        $orders = $user->orders;
        $payment = $orders->map(fn($order) => $order->payment)
            ->flatten()
            ->where('uuid', $uuid)->firstOrFail();
        return response()->json((new PaymentResponse($payment))->toArray());
    }

    public function createPayment(Request $request):JsonResponse
    {
        $data = $request->all();
        if ($data['type'] === 'credit_card') {
            $now = date_format(new \DateTime(), "m/y");
            if ($data['details']['expire_date'] < $now) {
                return response()->json(['error' => 'Expired card'], 400);
            }
        }
        $validator = \Validator::make($request->all(), Payment::rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = authUser($request);
        $orders = $user->orders;
        $order = $orders->where('uuid', $data['order_uuid'])->firstOrFail();
        if ($order->payment !== null) {
            return response()->json(['message' => 'Payment already exists'], 400);
        }
        if ($order->orderStatus->title !== OrderStatus::STATUS_PENDING) {
            return response()->json(['message' => 'Order is not pending'], 400);
        }
        $payment = $this->action->create($order, $data);
        return response()->json((new PaymentResponse($payment))->toArray());
    }

    public function updatePayment(Request $request, string $uuid):JsonResponse
    {
        $data = $request->all();
        if ($data['type'] === 'credit_card' && $data['details']['expire_date'] !== null) {
            $now = date_format(new \DateTime(), "m/y");
            if ($data['details']['expire_date'] < $now) {
                return response()->json(['error' => 'Expired card'], 400);
            }
        }
        $validator = \Validator::make($request->all(), Payment::rules());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = authUser($request);
        $payment = Payment::whereUuid($uuid)->firstOrFail();
        assert($payment->order instanceof Order);
        if ($payment->order->user->id !== $user->id) {
            return response()->json(['message' => 'Payment does not belong to user'], 400);
        }
        $payment = $this->action->update($payment, $data);
        return response()->json((new PaymentResponse($payment))->toArray());
    }

    public function deletePayment(Request $request, string $uuid):JsonResponse
    {
        $user = authUser($request);
        $payment = Payment::whereUuid($uuid)->firstOrFail();
        assert($payment->order instanceof Order);
        if ($payment->order->user->id !== $user->id) {
            return response()->json(['message' => 'Payment does not belong to user'], 400);
        }
        $this->action->delete($payment);
        return response()->json(['message' => 'Payment deleted']);
    }
}
