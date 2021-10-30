<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function productPackage()
    {
        return $this->hasMany('App\Models\ProductPackage', 'product_package_id', 'id');
    }
}
