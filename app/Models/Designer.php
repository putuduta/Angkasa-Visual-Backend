<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    use HasFactory;
    protected $table = 'designers';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];

    public function designer()
    {
        return $this->belongsTo('App\Models\User', 'users', 'id');
    }

    public function skills()
    {
        return $this->hasMany('App\Models\DetailSkill', 'detail_skills', 'designer_id');
    }  

    public function order()
    {
        return $this->hasMany('App\Models\DetailOrder', 'detail_orders', 'designer_id');
    }

    public function chat()
    {
        return $this->hasMany('App\Models\Chat', 'chats', 'designer_id');
    }
}
