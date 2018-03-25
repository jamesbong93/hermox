<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkAuthOrders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $order_id = $request->route()->parameters()['order_id'];
        $order = Order::find($order_id);
        if (Auth::user()->id !== $order->user_id) {
            return redirect('/home');
        }
        return $next($request);
    }
}
