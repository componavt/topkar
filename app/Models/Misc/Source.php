<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Toponym;

class Source extends Model
{
    use HasFactory;
    protected $fillable = ['short_ru','name_ru', 'short_en', 'name_en'];
    public $timestamps = false;
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getShortAttribute;
    use \App\Traits\Methods\search\byName;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Toponyms;
    
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
        
        $recs = self::orderBy('name_ru');
        
        $recs = self::searchByName($recs, $url_args['search_name']);
        
        return $recs;
    }    
    
}
