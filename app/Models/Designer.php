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
}
