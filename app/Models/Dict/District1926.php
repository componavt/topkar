<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Dict\Region;

class District1926 extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    protected $table = 'districts1926';
    public $timestamps = false;
    protected $fillable = ['region_id','name_en','name_ru'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\search\byName;
    use \App\Traits\Methods\sortList;
    
    use \App\Traits\Relations\BelongsTo\Region;
    use \App\Traits\Relations\HasMany\Selsovets1926;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function toponyms()
    {
        $district_id = $this->id;
        return Toponym::whereIn('settlement1926_id', function($q2) use ($district_id) {
                    $q2 -> select ('id') -> from ('settlements1926')
                        -> whereIn('selsovet_id', function($q3) use ($district_id) {
                            $q3 -> select ('id') -> from ('selsovets1926')
                                -> where('district1926_id', $district_id);
                        });
                });
    }
    
    /** Gets array of search parameters.
     * 
     * @param type $request
     * @return type
     */
    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_regions'     => (array)$request->input('search_regions'),
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
        
        if ($url_args['search_regions']) {
            $districts = $districts->whereIn('region_id',$url_args['search_regions']);
        }         
        return $districts;
    }    
}
