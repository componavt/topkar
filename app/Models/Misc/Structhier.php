<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structhier extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500; //Stop tracking revisions after 500 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    public $timestamps = false;
    protected $fillable = ['name_ru',  'name_en', 'parent_id'];
    const SHORT_NAMES = [
        1 => ['ru'=>'Рус.', 'en'=>'Rus.'], 
        2 => ['ru'=>'Приб.-фин.', 'en'=>'Balt-fin.'], 
        10 => ['ru'=>'Саам.', 'en'=>'Sam.']];
    
    use \App\Traits\Methods\getNameAttribute;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }
    
    public function getShortNameAttribute()
    {
        $locale = app()->getLocale();
        $short_names = self::SHORT_NAMES;
        if (isset($short_names[$this->id][$locale])) {
            return $short_names[$this->id][$locale];
        }
        return $this->name;
    }
    
    public function nameToString() {
        $out = $this->name;
        if ($this->parent) {
            $out = $this->parent->short_name. ' '. mb_strtolower($out);
        }
        return $out;
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
