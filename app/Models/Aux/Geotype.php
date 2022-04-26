<?php

namespace App\Models\Aux;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geotype extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['short_ru','name_ru', 'desc_ru', 'short_en', 'name_en', 'desc_en'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
}
