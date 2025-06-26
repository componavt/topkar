<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Toponym;
use App\Models\Misc\Structhier;

class Struct extends Model
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
    
    public $timestamps = false;
    protected $fillable = ['name_ru',  'name_en', 'structhier_id'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\sortList;
    
    public static function boot()
    {
        parent::boot();
    }
    
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
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_structhiers'   => (array)$request->input('search_structhiers'),
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
                    'sort_by' => $request->input('sort_by'),
                ];
        
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
    
    public static function search(Array $url_args) {
        $structs = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC'); 

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
