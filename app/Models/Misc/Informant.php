<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Toponym;

class Informant extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    public $timestamps = false;
    protected $fillable = ['name_en','name_ru','birth_date'];
    const SortList=['name_ru', 'id'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\sortList;
    
    use \App\Traits\Relations\BelongsToMany\Events;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function toponyms(){
        $informant_id = $this->id;
        return Toponym::whereIn('id', function ($q1) use ($informant_id) {
                 $q1->select('toponym_id')->from('events')
                    ->whereIn('id', function ($q2) use ($informant_id) {
                    $q2->select('event_id')->from('event_informant')
                       ->whereInformantId($informant_id);
                 });
               });
    }    
    
    /** Gets list of informant
     * 
     * @return Array [1=>'Vepsian',..]
     */
    public static function getList()
    {     
        $locale = app()->getLocale();
        
        $informants = self::orderBy('name_'.$locale)->get();
        
        $list = array();
        foreach ($informants as $row) {
            $list[$row->id] = $row->informantString();
        }
        
        return $list;         
    }
    
    /**
     * Gets full information about informant
     * 
     * i.e. "Калинина Александра Леонтьевна, 1909"
     * 
     * @return String
     */
    public function informantString()
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name;
        }
        
        if ($this->birth_date) {
            $info[] = $this->birth_date;
        }
        
        return join(', ', $info);
    }   
    
    public static function search(Array $url_args) {
        $informants = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC');  
        
        $informants = self::searchByName($informants, $url_args['search_name']);

        if ($url_args['search_date']) {
            $informants = $informants->where('birth_date',$url_args['search_date']);
        } 

        if ($url_args['search_id']) {
            $informants = $informants->where('id',$url_args['search_id']);
        } 
        return $informants;
    }
    
    public static function searchByName($informants, $name) {
        if (!$name) {
            return $informants;
        }
        return $informants->where(function($q) use ($name){
                        $q->where('name_en','like', $name)
                          ->orWhere('name_ru','like', $name);
                });
    }

    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'in_desc'     => (int)$request->input('in_desc'),
                    'search_date'   => (int)$request->input('search_date'),
                    'search_id'  => (int)$request->input('search_id'),
                    'search_name'   => $request->input('search_name'),
                    'sort_by' => $request->input('sort_by'),
                ];
        
        $url_args['search_date'] = $url_args['search_date'] ? $url_args['search_date'] : NULL;
        
        $url_args['search_id'] = $url_args['search_id'] ? $url_args['search_id'] : NULL;
        
        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by']= $sort_list[0];
        }
        return remove_empty_elems($url_args);
    }
}
