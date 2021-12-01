<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function designer()
    {
        return $this->belongsTo('App\Models\Designer', 'designers', 'designer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users', 'user_id');
    }

    public function detailOrder()
    {
        return $this->belongsTo('App\Models\DetailOrder', 'detail_orders', 'detail_order_id');
    }
}
