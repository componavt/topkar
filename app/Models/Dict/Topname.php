<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topname extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id', 'name', 'name_for_search'];
     public $timestamps = false;
}
