<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['region_id','name_en','name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\search\byName;
    
    use \App\Traits\Relations\BelongsTo\Region;
    use \App\Traits\Relations\BelongsToMany\Settlements;
    use \App\Traits\Relations\HasMany\Toponyms;
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_regions'     => (array)$request->input('search_regions'),
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
        
        $districts = self::orderBy('region_id')->orderBy('name_ru');
        
        $districts = self::searchByName($districts, $url_args['search_name']);
        
        if ($url_args['search_regions']) {
            $districts = $districts->whereIn('region_id',$url_args['search_regions']);
        }         
        return $districts;
    }    
}
