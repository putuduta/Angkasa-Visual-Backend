<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;    
    protected $table = 'detail_orders';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function headerOrder()
    {
        return $this->belongsTo('App\Models\HeaderOrder', 'header_orders', 'order_id');
    }

    public function designer()
    {
        return $this->belongsTo('App\Models\Designer', 'designers', 'designer_id');
    }

    public function productPackage()
    {
        return $this->belongsTo('App\Models\ProductPackage', 'product_packages', 'product_package_id');
    }

    public function chat()
    {
        return $this->hasMany('App\Models\Chat', 'chats', 'detail_order_id_id');
    }
}
