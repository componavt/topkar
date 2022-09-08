<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Toponym;

class Recorder extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_en','name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    
    use \App\Traits\Relations\BelongsToMany\Events;

    public function toponyms(){
        $recorder_id = $this->id;
        return Toponym::whereIn('id', function ($q1) use ($recorder_id) {
                 $q1->select('toponym_id')->from('events')
                    ->whereIn('id', function ($q2) use ($recorder_id) {
                    $q2->select('event_id')->from('event_recorder')
                       ->whereRecorderId($recorder_id);
                 });
               });
    }    
    
    /** Gets list of recorders
     * 
     * @return Array [1=>'Онегина Нина Федоровна',..]
     */
    public static function getList()
    {     
        $locale = app()->getLocale();;
        
        $recorders = self::orderBy('name_'.$locale)->get();
        
        $list = array();
        foreach ($recorders as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
    
    public static function search(Array $url_args) {
        $locale = app()->getLocale();;
        $recorders = self::orderBy('name_'.$locale);  
        
        $recorders = self::searchByName($recorders, $url_args['search_name']);

        if ($url_args['search_id']) {
            $recorders = $recorders->where('id',$url_args['search_id']);
        } 
        return $recorders;
    }
    
    public static function searchByName($recorders, $name) {
        if (!$name) {
            return $recorders;
        }
        return $recorders->where(function($q) use ($name){
                        $q->where('name_en','like', $name)
                          ->orWhere('name_ru','like', $name);
                });
    }

    public static function urlArgs($request) {
        $url_args = url_args($request) + [
                    'search_id'  => (int)$request->input('search_id'),
                    'search_name'   => $request->input('search_name'),
                ];
        
        $url_args['search_id'] = $url_args['search_id'] ? $url_args['search_id'] : NULL;
        
        return $url_args;
    }    
    
}
