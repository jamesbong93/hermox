<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promotion_code';
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
    protected $fillable = ['name'];

    /**
     * Get the orders associated with the promotion_code.
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'promotion_code_id', 'id');
    }
}
