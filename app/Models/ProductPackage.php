<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;
    protected $table = 'product_packages';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products', 'id');
    }

    public function cart()
    {
        return $this->belongsTo('App\Models\Cart', 'carts', 'product_package_id');
    }

    public function detailOrder()
    {
        return $this->hasMany('App\Models\DetailOrder', 'detail_orders', 'id');
    }
}
