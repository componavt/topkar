<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structhier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_ru',  'name_en', 'parent_id'];
    const SHORT_NAMES = [
        1 => ['ru'=>'Рус.', 'en'=>'Rus.'], 
        2 => ['ru'=>'Приб.-фин.', 'en'=>'Balt-fin.'], 
        10 => ['ru'=>'Саам.', 'en'=>'Sam.']];
    
    use \App\Traits\Methods\getNameAttribute;
    
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }
    
    /** Gets list of objects
     * 
     * @return Array [1=>'Object name',..]
     */
    public static function getGroupedList()
    {     
        $locale = app()->getLocale();
        $list = [];
        
        $parents = self::whereNull('parent_id')->orderBy('name_'.$locale)->get();
        foreach ($parents as $parent) {
            $objects = self::whereParentId($parent->id)
                           ->orderBy('name_'.$locale)->get();
            foreach ($objects as $row) {
                $list[$parent->name][$row->id] 
                        = self::SHORT_NAMES[$parent->id][$locale] 
                        .' '. mb_strtolower($row->name);
            }
        }                
        return $list;         
    }
    
}
