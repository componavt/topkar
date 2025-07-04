<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geotype extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    public $timestamps = false;
    protected $fillable = ['short_ru','name_ru', 'desc_ru', 'short_en', 'name_en', 'desc_en'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getNameById;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getShortNameAttribute;
    use \App\Traits\Methods\search\byName;
    use \App\Traits\Methods\sortList;
    
    use \App\Traits\Relations\HasMany\Toponyms;
    
    public static function boot()
    {
        parent::boot();
    }
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_name'    => $request->input('search_name'),
                    'sort_by' => $request->input('sort_by'),
                ];
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
    
    /** Search district by various parameters. 
     * 
     * @param array $url_args
     * @return type
     */
    public static function search(Array $url_args) {
        
        $districts = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');
        
        $districts = self::searchByName($districts, $url_args['search_name']);
        
        return $districts;
    }    
}
