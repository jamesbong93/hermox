<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_products';
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
    protected $fillable = ['orders_id', 'product_id'];
    
    /**
     * Get the user associated with the orders.
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'orders_id', 'id');
    }

    /**
     * Get the shipping country associated with the products.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
