<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
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
    protected $fillable = ['description', 'selling_price', 'retail_price', 'brand_id', 'quantity'];
    
    /**
     * Get the brand associated with the products.
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

    /**
     * Get the orders associated with the products.
     */
    public function productOrders()
    {
        return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
    }

    /**
     * Declare many to many relation for order_products
     */
    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'orders_products', 'product_id', 'order_id');
    }
}
