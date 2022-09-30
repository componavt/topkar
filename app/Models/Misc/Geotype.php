<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geotype extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['short_ru','name_ru', 'desc_ru', 'short_en', 'name_en', 'desc_en'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getShortNameAttribute;
    use \App\Traits\Methods\search\byName;
    
    use \App\Traits\Relations\HasMany\Toponyms;
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_name'    => $request->input('search_name'),
                ];
        return $url_args;
    }
    
    /** Search district by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $districts = self::orderBy('name_ru');
        
        $districts = self::searchByName($districts, $url_args['search_name']);
        
        return $districts;
    }    
}
