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
                <h5>{{ $product->name }}</h5>
                <div class="ratings">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                </div>
                <p>{{ $product->description }}</p>
                <hr class="line">
                <div class="row">
                    <div class="col-md-8 col-sm-6">
                        <span class="price">RM {{ $product->selling_price }}</span>
                        <span class="price_discounted">RM {{ $product->retail_price }}</span>
                        <select id="product-quantity_{{ $product->id }}">
                            @for ($i = 1; $i <= $product->quantity; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <button class="btn btn-success right buy-btn" data-productId="{{ $product->id }}"> BUY ITEM</button>
                    </div>
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
    });
</script>
@endsection