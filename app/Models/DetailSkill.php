<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSkill extends Model
{
    use HasFactory;
    protected $table = 'detail_skills';
    protected $primaryKey = 'id';
    protected $timestamp = true;
    protected $guarded = [];


    public function designer()
    {
        return $this->belongsTo('App\Models\Designer', 'designers', 'id');
    }
}
