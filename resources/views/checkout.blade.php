@extends('layouts.app')
@section('after-styles')
    <link href="/css/order.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    	.mat-input{
            margin: 2% auto;
            width: 50%;    
        }
        h4 {
            margin-top: 20px;
            font-weight: bold;
        }
        .mat-input-outer{
            display: table;
            width: 100%;
            position: relative;
        }
        .mat-input-outer input{
            height: 50px;
            border-radius: 0;
            border: none;
            width: 100%;    
            padding: 0 15px;
            font-family: calibri;
            font-size: 20px;
            font-style: italic;
        }
        .mat-input-outer label{
            font-family: calibri;
            font-size: 20px;
            left: 15px;
            position: absolute;
            top: 18px;
            font-style: italic;
            transition: .2s;
            color: #000;
            cursor: text;
            font-weight: normal;
            opacity: 0.4;
            filter: alpha(opacity=40);
        }
        .mat-input-outer .border{
            height: 1px;
            background: #000;
            transition: .3s;
            -webkit-transition: .3s;
            -ms-transition: .3s;
        }
        .mat-input-outer .border::before{
            content: " ";
            display: table;
            height: 3px;
            width: 0%;
            background: transparent;
            transition: .3s;
            -webkit-transition: .3s;
            -ms-transition: .3s;
            margin: 0 auto;
        }
        .mat-input-outer input:focus ~ .border{
            background: transparent;
        }
        .mat-input-outer input:focus ~ .border::before{
            width: 100%;
            background: purple;
        }
        .mat-input-outer input + label.active{
            left: 5px;
            top: -25px;
            color: purple;
            opacity: 1;
            filter: alpha(opacity=100);
        }
        .price_discounted {
            color: #343a40;
            text-align: center;
            text-decoration: line-through;
            font-size: 70%;
        }
    </style>
@endsection
@section('content')
<div class="container">
	<h2 class="text-center">Check Out</h2>
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    <div class="preview-pic tab-content">
                        <div class="tab-pane active text-center" id="pic-1">
                            <img src="http://placehold.it/500x400" style="width: 350px;"/>
                        </div>
                    </div>
                </div>
                <div class="details col-md-6">
                    <div class="panel panel-default text-center">
                        <h4>Name</h4>
                        <hr>
                    	<p>{{ $product->name }}</p>
                    </div>
                    <div class="panel panel-default text-center">
                        <h4>Brand</h4>
                        <hr>
                    	<p>{{ $product->brand->name }}</p>
                    </div>
                    <div class="panel panel-default text-center">
                        <h4>Selling Price</h4>
                        <hr>
                        <p>RM {{ $product->selling_price }} <span class="price_discounted">RM {{ $product->retail_price }}</span></p>
                    </div>
                    <div class="panel panel-default text-center">
                        <h4>Purchase Quantity</h4>
                        <hr>
                    	<p>{{ $purchase_quantity }}</p>
                    </div>
                    <div class="panel panel-default text-center">
                            <h4>Shipping Country</h4>
                            <hr>
                            <div class="col-md-12">
                                <select id="shipping-country" name='beden' class="form-control" name="size">
                                    @foreach ($countries as $country) 
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                            </select>
                            </div>
                        <br>
                    </div>
                    <div class="panel panel-default text-center">
                        <h4>Promotion Code</h4>
                        <hr>
                        <div class="mat-input">
			                <div class="mat-input-outer">
			                    <input class="text-center" type="text" autocomplete="off"/ id="promotion-code">
			                    <div class="border"></div>
			                </div>
			                <span id="valid-code" class="text-success text-center" style="display: none;">This code is valid </span>
			                <span id="invalid-code" class="text-danger text-center" style="display: none;">This code is invalid </span>
                            <div class="text-center"><br>
                                <button class="btn btn-primary" id="promo-apply-btn" style="display: none;" type="button" onclick="applyPromoCode()">Apply</button>
                                <button class="btn btn-success" id="promo-check-btn" type="button" onclick="checkPromotionCode()">Check</button>
                                <button class="btn btn-warning" id="promo-change-btn" type="button" onclick="changePromoCode()" style="display: none;">Change</button>
                            </div>
			            </div>
                    </div>
                    <div class="panel panel-default text-center">
                        <h4>Total Price</h4>
                        <hr>
                        <p class="text-muted">RM {{ $product->selling_price * $purchase_quantity}} <span id="discount" style="display: none"> - RM <span></span></span> <span id="shipping-fee" style="display: none"> + RM <span></span></span> </p>
                        <h2><font color="purple">RM <span id="total-price"></span></h2>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="confirmOrder()">Confirm Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
@endsection

@section('after-scripts')
	<script type="text/javascript">


		function getTotalPrice() {
            var promotion_code = $('#promotion-code').val();
            if ($('#promo-change-btn').is(":hidden")) {
                promotion_code = '';
            }
			$.ajax({
                url: '{{ route("totalPrice") }}',
                type: 'get',
                dataType: 'json',
                data: {
                    'product_id': '{{ $product->id }}',
                    'purchase_quantity': '{{ $purchase_quantity }}',
                    'shipping_country': $('#shipping-country').val(),
                    'promotion_code': promotion_code
                },
                success: function(data) {
                    if (data['status'] == 'success') {
                        $('#total-price').text(data['totalPrice']);
                        if (data['discount'] !== 0) {
                            $('#discount').show();
                            $('#discount').find('span').text(data['discount']);
                        } else {
                            $('#discount').hide();
                        }
                        if (data['shippingFee'] !== 0) {
                            $('#shipping-fee').show();
                            $('#shipping-fee').find('span').text(data['shippingFee']);
                        } else {
                            $('#shipping-fee').hide();
                        }
                    }
                }
            });
		}

		function checkPromotionCode() {
			$.ajax({
                url: '{{ route("checkPromotionCode") }}',
                type: 'get',
                dataType: 'json',
                data: {
                    'product_id': '{{ $product->id }}',
                    'purchase_quantity': '{{ $purchase_quantity }}',
                    'promotion_code': $('#promotion-code').val()
                },
                success: function(data) {
                	if (data['status'] == 'success') {
                        $('#promo-apply-btn').show();
                        $('#invalid-code').hide();
                        $('#valid-code').text(data['content']);
                		$('#valid-code').show();
                	} else {
                        $('#promo-apply-btn').hide();
                        $('#valid-code').hide();
                        $('#invalid-code').text(data['content']);
                		$('#invalid-code').show();
                	}
                }
            });
		}

        function applyPromoCode() {
            $("#promotion-code").prop('disabled', true);
            $("#promo-apply-btn").hide();
            $("#promo-check-btn").hide();
            $("#promo-change-btn").show();
            getTotalPrice();

        }

        function changePromoCode() {
            $('#discount').hide();
            $('#valid-code').hide();
            $("#promo-change-btn").hide();
            $("#promotion-code").val('');
            getTotalPrice();
            $("#promotion-code").prop('disabled', false);
            $("#promo-check-btn").show();
        }

        function confirmOrder() {
            var promotion_code = $('#promotion-code').val();
            if ($('#promo-change-btn').is(":hidden")) {
                promotion_code = '';
            }
            var r = confirm("Are you sure to complete this order?");
            if (r == true) {
                $.ajax({
                    url: '{{ route("completeOrder") }}',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'product_id': '{{ $product->id }}',
                        'purchase_quantity': "{{ $purchase_quantity }}",
                        'promotion_code': promotion_code,
                        'net_price': $('#total-price').text(),
                        'discount': $('#discount').text(),
                        'shipping_fee': $('#shipping-fee').text(),
                        'shipping_country': $('#shipping-country').val(),
                    },
                    success: function(data) {
                        if (data['status'] == 'success') {
                            alert('Congratulation you have completed your order!')
                            window.location.href = data['redirect'];   
                        }
                    }
                });
            }
        }

		$(function () {
			getTotalPrice();

			$('#shipping-country').change(function(){
				getTotalPrice();
			});

            $('.mat-input-outer label').click(function () {
                $(this).prev('input').focus();
            });
            $('.mat-input-outer input').focusin(function () {
                $(this).next('label').addClass('active');
            });
            $('.mat-input-outer input').focusout(function () {
                if (!$(this).val()) {
                    $(this).next('label').removeClass('active');
                } else {
                    $(this).next('label').addClass('active');
                }
            });
        });
	</script>
@endsection