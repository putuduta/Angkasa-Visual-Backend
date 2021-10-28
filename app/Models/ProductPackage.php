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
}
