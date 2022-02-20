<?php

namespace App\Actions;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Orders\Payment;
use DB;
use Illuminate\Http\JsonResponse;

class PaymentAction
{

    public function create(Order $order, array $data): Payment
    {
        return DB::transaction(function() use ($order, $data){
            $payment =  Payment::create([
                                       'type' => $data['type'],
                                       'details' => $data['details'],
                                   ]);
            $order->order_status_id = OrderStatus::whereTitle(OrderStatus::STATUS_PAID)->firstOrFail()->id;
            $order->payment_id = $payment->id;
            $order->save();
            return $payment;
        });
    }

    public function update(Payment $payment, array $data): Payment
    {
        return DB::transaction(function() use ($payment, $data){
            $payment->update([
                               'type' => $data['type'],
                               'details' => $data['details'],
                           ]);
            return $payment;
        });
    }

    public function delete(Payment $payment):JsonResponse
    {
        return DB::transaction(function() use ($payment){
            $order = $payment->order;
            assert($order instanceof Order);
            $order->payment_id = null;
            $order->order_status_id = OrderStatus::whereTitle(OrderStatus::STATUS_PENDING)->firstOrFail()->id;
            $order->save();
            $payment->delete();
            return response()->json(['message' => 'Payment deleted successfully']);
        });
    }
}
