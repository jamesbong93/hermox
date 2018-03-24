<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromotionCode;
use Illuminate\Http\Request;
use Illuminate\Routing\redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product_id, $purchase_quantity)
    {
        $product = Product::find($product_id);
        $countries = Country::get();
        return view('checkout', compact('product', 'purchase_quantity', 'countries'));
    }

    /**
     * process selected orders
     *
     * @return \Illuminate\Http\Response
     */
    public function processOrder(Request $request)
    {
        $redirect = route("checkout", [$request['product_id'], $request['product_quantity']]);
        return response()->json(['status' => 'success', 'redirect' => $redirect]);
    }

    /**
     * get total price
     *
     * @return \Illuminate\Http\Response
     */
    public function totalPrice(Request $request)
    {
        $order = $this->getTotalPrice($request);
        return response()->json(['status' => 'success', 'totalPrice' => $order[0]['totalPrice'], 'discount' => $order[0]['discount'], 'shippingFee' => $order[0]['shippingFee'] ]);
    }

    public function getTotalPrice($request) {
        $order = [];
        $product = Product::find($request['product_id']);
        $price = $product->selling_price * $request['purchase_quantity'];
        $discount = 0;
        $promotion_code = $request['promotion_code'];
        if ($promotion_code) {
            $discount = $this->getDiscount($promotion_code, $price, $request['purchase_quantity']);
        }
        $price = $price - $discount;
        $shippingFee = $this->shippingFee($request['shipping_country'], $price, $request['purchase_quantity']);
        $totalPrice = round(($price + $shippingFee), 2);
        array_push($order, ['totalPrice' => $totalPrice, 'discount' => $discount, 'shippingFee' => $shippingFee]);
        return $order;
    }

    public function shippingFee($shipping_country, $price, $purchase_quantity)
    {
    	$shippingFee = 0;
    	switch ($shipping_country) {
    		case 1:
    			if ($purchase_quantity < 2 && $price < 150) {
    				$shippingFee = 10;
    			}
    			break;
    		case 2:
    			if ($price < 300) {
    				$shippingFee = 20;
    			} 
    			break;
    		case 3:
    			if ($price < 300) {
    				$shippingFee = 25;
    			} 
    			break;
    		default:
    			break;
    	}
    	return $shippingFee;
    }

    public function getDiscount($promotion_code, $price, $purchase_quantity)
    {
        $discount = 0;
        switch ($promotion_code) {
            case 'OFF5PC':
                if ($purchase_quantity > 1) {
                    $discount = $price * 0.05;
                }
                break;
            case 'GIVEME15':
                if ($price > 100) {
                    $discount = 15;
                }
                break;
        }
        return round($discount, 2);
    }

    public function checkPromotionCode(Request $request)
    {
        $product = Product::find($request['product_id']);
        $purchase_quantity = $request['purchase_quantity'];
        $promotion_code = $request['promotion_code'];
        $price = $product->selling_price * $purchase_quantity;

    	if (!PromotionCode::where(DB::raw('BINARY `name`'), $promotion_code)->exists()) {
        	return response()->json(['status' => 'failed', 'content' => 'This promo code is invalid']);
    	}
        switch ($promotion_code) {
            case 'OFF5PC':
                if ($purchase_quantity < 2) {
                    return response()->json(['status' => 'failed', 'content' => 'Not eligble for this promo code.']);
                }
                break;
            case 'GIVEME15':
                if ($price < 100) {
                    return response()->json(['status' => 'failed', 'content' => 'Not eligble for this promo code.']);
                }
                break;
        }
        return response()->json(['status' => 'success', 'content' => 'This promo code is valid']);
    }

    public function completeOrder(Request $request)
    {
        $datas = $this->getTotalPrice($request);
        $product = Product::find($request['product_id']);
        $promotion = PromotionCode::where('name', $request['promotion_code'])->first();
        foreach ($datas as $data) {
            //save new order
            $order = new Order([
                'net_price' => $data['totalPrice'],
                'list_price' => $product->selling_price * $request['purchase_quantity'],
                'discount' => $data['discount'],
                'shipping_fee' => $data['shippingFee'],
                'purchase_quantity' => $request['purchase_quantity'],
                'user_id' => Auth::user()->id,
                'promotion_code_id' => ($promotion ? $promotion->id : null),
                'shipping_country_id' => $request['shipping_country']
            ]);
            $order->save();
            $order->products()->sync([$product->id]);
        }
        return response()->json(['status' => 'success']);
    }
}
