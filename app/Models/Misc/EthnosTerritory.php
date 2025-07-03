<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthnosTerritory extends Model
{
//    use HasFactory;
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    public static function getNameById($id) {
        $obj = self::find($id);
        return $obj ? $obj->name : '';
    }
     
}
