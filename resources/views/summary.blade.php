@extends('layouts.app')
@section('after-styles')
    <link href="/css/order.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="container">
    <h2 class="text-center">Order Summary</h2>
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    <div class="preview-pic tab-content">
                        <div class="tab-pane active text-center" id="pic-1">'
                            <img src="http://placehold.it/500x400"/ style="width: 350px;"><br><br>
                            <h4><strong>{{ $orderProduct->brand->name }}</strong></h4>
                            <h5 class="product-name" title="{{ $orderProduct->name }}">{{ $orderProduct->name }}</h5>
                        </div>
                    </div>
                </div>
                <div class="details col-md-6">
                     <!-- ORDER -->
                    <div class="panel panel-default">
                        <div class="text-center mt-4">
                            <h4><strong>Order Number: </strong>ORD-<span>{{ $order->id }}</span></h4>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <small>Unit Price</small>
                                <div class="pull-right"><span>RM </span><span>{{ $orderProduct->selling_price  }}</span></div>
                            </div>
                            <div class="col-md-12">
                                <small>Quantity</small>
                                <div class="pull-right"><span>{{ $order->purchase_quantity }}</span></div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <small>Total Price</small>
                                <div class="pull-right"><span>RM </span><span>{{ $order->list_price }}</span></div>
                            </div>
                            <div class="col-md-12">
                                <small>Promotion Code</small>
                                <div class="pull-right"><span>{{ ($order->promotionCode) ? $order->promotionCode->name : ''  }}</span></div>
                            </div>
                            <div class="col-md-12">
                                <small>Discount</small>
                                <div class="pull-right"><span>RM </span><span>{{ $order->discount }}</span></div>
                            </div>
                            <div class="col-md-12">
                                <small>Delivery To</small>
                                <div class="pull-right"><span>{{ $order->shippingCountry->name }}</span></div>
                            </div>
                            <div class="col-md-12">
                                <small>Shipping Fee</small>
                                <div class="pull-right"><span>RM </span><span>{{ $order->shipping_fee }}</span></div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <strong>Payment Required</strong>
                                <div class="pull-right"><span>RM </span><span>{{ $order->net_price }}</span></div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection