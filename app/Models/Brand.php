<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brands';
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
    protected $fillable = ['name', 'description'];
    
    /**
     * Get the brand associated with the products.
     */
    public function brand()
    {
        return $this->hasMany('App\Models\Product', 'brand_id', 'id');
    }
}
