<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Toponym;
use App\Models\Misc\Structhier;

class Struct extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_ru',  'name_en', 'structhier_id'];
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    /**
     * The toponyms that belong to the structure. (many to many relation)
     */
    public function toponyms()
    {
        return $this->belongsToMany(Toponym::class);
    }
    
    public function structhier()
    {
        return $this->belongsTo(Structhier::class);
    }
    
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_structhiers'   => (array)$request->input('search_structhiers'),
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
                ];
        
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        $structs = self::orderBy('name_ru'); 

        $structs = self::searchByStructhiers($structs, $url_args['search_structhiers']);
        $structs = self::searchByID($structs, $url_args['search_id']);
        $structs = self::searchByName($structs, $url_args['search_name']);
//dd(to_sql($structs));                                
        return $structs;
    }
    
    public static function searchByName($places, $place_name) {
        if (!$place_name) {
            return $places;
        }
        return $places->where(function($q) use ($place_name){
                            $q->where('name_ru','like', $place_name)
                              ->orWhere('name_en','like', $place_name);           
                });
    }
    
    public static function searchByStructhiers($structs, $structhiers) {
        
        if(!sizeof($structhiers)) {
            return $structs;
        }
        
        return $structs->whereIn('structhier_id', $structhiers);
    }
    
    public static function searchByID($places, $search_id) {
        if (!$search_id) {
            return $places;
        }
        return $places->where('id',$search_id);
    }
}
