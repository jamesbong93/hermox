@extends('layouts.app')
@section('after-styles')
    <style type="text/css">
        h4{
            font-weight: 600;
        }
        p{
            font-size: 12px;
            margin-top: 5px;
        }
        .price{
            font-size: 25px;
            margin: 0 auto;
            color: #333;
        }
        .right{
            float:right;
            border-bottom: 2px solid #4B8E4B;
        }
        .product-name {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .thumbnail{
            opacity:0.70;
            -webkit-transition: all 0.5s; 
            transition: all 0.5s;
        }
        .thumbnail:hover{
            opacity:1.00;
            box-shadow: 0px 0px 10px #4bc6ff;
        }
        .line{
            margin-bottom: 5px;
        }
        @media screen and (max-width: 770px) {
            .right{
                float:left;
                width: 100%;
            }
        }
        img {
            width: 100%;
        }
        .price_discounted {
            color: #343a40;
            text-align: center;
            text-decoration: line-through;
        }
        .sold-out {
            margin-top: 20px;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <!-- BEGIN PRODUCTS -->
        @foreach ($products as $product)
        <div class="col-md-4 col-sm-6" style="margin-top: 20px;">
            <span class="thumbnail">
                <img src="http://placehold.it/500x400" alt="..."><br><br>
                <h4>{{ $product->brand->name }}</h4>
                <h5 class="product-name" title="{{ $product->name }}">{{ $product->name }}</h5>
                <div class="ratings">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                </div>
                <hr class="line">
                <div class="row">
                    <div class="col-md-8">
                        <span class="price">RM {{ $product->selling_price }}</span>
                        <span class="price_discounted">RM {{ $product->retail_price }}</span>
                    </div>
                    @if ($product->quantity > 1)
                    <div class="col-md-12 input-group mb-3 mt-3">
                        <div class="input-group-prepend">
                            <button type="button" data-productId="{{ $product->id }}" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field="">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <select class="form-control" id="product-quantity_{{ $product->id }}" name="quantity" data-quantity="{{ $product->quantity }}" value="1">
                            @for ($i=1; $i<=$product->quantity; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="input-group-append">
                            <button type="button" data-productId="{{ $product->id }}" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary right buy-btn" data-productId="{{ $product->id }}"> BUY ITEM</button>
                    </div>
                    @else 
                    <div class="col-md-12 sold-out text-center">
                        <h2>Sold Out</h2>
                    </div>
                    @endif
                </div>
            </span>
        </div>
        @endforeach
        <!-- END PRODUCTS -->
    </div>
</div>
@endsection

@section('after-scripts')
<script type="text/javascript">
    $(document).ready(function(){
        // check out function
        $('.buy-btn').click(function () {
            var r = confirm("Confirm to purchase this product? ");
            if (r == true) {
                $.ajax({
                    url: '{{ route("processOrder") }}',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'product_id': $(this).attr("data-productId"),
                        'product_quantity': $("#product-quantity_" + $(this).attr("data-productId")) .val()
                    },
                    success: function(data) {
                        window.location.href = data['redirect'];
                    }
                });
            } else {
                
            }
        });

        var quantity = 1;

        $('.quantity-right-plus').click(function(e){
            e.preventDefault();
            var maxQuantity = $('#product-quantity_' + $(this).attr("data-productId")).attr("data-quantity");
            var quantity = parseInt($('#product-quantity_' + $(this).attr("data-productId")).val());
            if (quantity < maxQuantity) {
                $('#product-quantity_' + $(this).attr("data-productId")).val(quantity + 1);
            }
        });

        $('.quantity-left-minus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#product-quantity_' + $(this).attr("data-productId")).val());
            if(quantity > 1) {
                $('#product-quantity_' + $(this).attr("data-productId")).val(quantity - 1);
            }
        });
    });
</script>
@endsection