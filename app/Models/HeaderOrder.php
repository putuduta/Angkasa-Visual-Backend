<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderOrder extends Model
{
    use HasFactory;
    protected $table = 'header_orders';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\HeaderOrder', 'header_orders', 'user_id');
    }

    public function detailOrder()
    {
        return $this->hasMany('App\Models\DetailOrder', 'detail_orders', 'order_id');
    }
}
