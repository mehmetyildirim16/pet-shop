<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
</head>
<body>
<div class="col-xs-6">
    <div style="border-top: grey 5px double; border-bottom: grey 5px double;">
        <h5><b> Dear </b></h5>
        <p>
            {{$order->user->full_name}} <br>
            {{$order->user->address}} <br>
            {{$order->user->phone}} <br>
            {{$order->user->email}} <br>
            <br>
        </p>
    </div>
</div>
<div class="col-xs-6">
    <div style="border-top: grey 5px double; border-bottom: grey 5px double;">
        <h5><b> Invoice </b></h5>
        <p>
            <b> Invoice Number: </b> {{$order->id}} <br>
            <b> Invoice Date: </b> {{$order->created_at}} <br>
            <b> Payment Method: </b> {{$order->payment->full_type}} <br>
            <b> Payment Status: </b> {{($order->orderStatus->title)}} <br>
            <br>
        </p>
    </div>
</div>
<table class="table">
    <thead class="thead-light">
    <tr>
        <th>Uuid</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
    </tr>
    </thead>
    <tr>
        @php
            $total = 0;
        @endphp
        @foreach($order->products as $item)
            @php
                $product = App\Models\Products\Product::whereUuid($item['product'])->firstOrFail();
                $total += $product->price * $item['quantity'];
            @endphp
            <td>{{$product->uuid}}</td>
            <td>{{$product->title}}</td>
            <td>{{$item['quantity']}}</td>
            <td>{{$product->price}}</td>
            <td>{{$product->price * $item['quantity']}}</td>
        @endforeach
    </tr>

    <tbody>
    </tbody>

</table>
<div class="row">
    <div class="col-6">

    </div>
    <div class="col-6">
        <div style="border-top: grey 5px double; border-bottom: grey 5px double;">
            <p>
                <b> Sub Total: </b> {{$total}} <br>
                <b> Delivery Fee: </b> {{$order->delivery_fee}} <br>
                <b> Total: </b> {{$total + $order->delivery_fee}} <br>
                <br>
            </p>
        </div>
    </div>
</div>
</body>
</html>
