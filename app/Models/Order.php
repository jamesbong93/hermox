<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    /**
     * The database primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['net_price', 'list_price', 'discount', 'shipping_fee', 'user_id', 'promotion_code_id', 'shipping_country_id'];
    
    /**
     * Get the user associated with the orders.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Get the shipping country associated with the orders.
     */
    public function shippingCountry()
    {
        return $this->belongsTo('App\Models\Country', 'shipping_country_id', 'id');
    }

    /**
     * Get the promotionCode country associated with the orders.
     */
    public function promotionCode()
    {
        return $this->belongsTo('App\Models\PromotionCode', 'promotion_code_id', 'id');
    }

    /**
     * Get the products associated with the orders.
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order', 'id');
    }

    /**
     * Declare many to many relation for order_products
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'orders_products', 'order_id', 'product_id');
    }
}
