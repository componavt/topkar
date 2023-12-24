<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
//    use HasFactory;
    
    public $timestamps = false;
    //protected $fillable = ['name_ru', 'code', 'sequence_number'];
    
    public function identifiableName()
    {
        return $this->name;
    }    

    /** Gets name of this lang, takes into account locale.
     * 
     * @return String
     */
    public function getNameAttribute() : String
    {
        $locale = app()->getLocale();
        if ($locale == 'en' && $this->name_en) {
            return $this->name_en;
        }
        return $this->name_ru;
    }

    public function getShortAttribute() : String
    {
        $locale = app()->getLocale();
        if ($locale == 'en') {
            return $this->code;
        }
        return mb_substr($this->name_ru,0,3).'.';
    }

    /** Gets ID of this lang by code, takes into account locale.
     * 
     * @return int
     */
    public static function getIDByCode($code) : Int
    {
        $lang = self::where('code',$code)->first();
        if ($lang) {
            return $lang->id;
        }
    }
           
    /** Gets name of this lang by code, takes into account locale.
     * 
     * @return String
     */
    public static function getNameByCode($code) : String
    {
        $lang = self::where('code',$code)->first();
        if ($lang) {
            return $lang->name;
        }
    }
           
    /** Gets name of this lang by code, takes into account locale.
     * 
     * @return String
     */
    public static function getNameByID($id) : String
    {
        $lang = self::where('id',$id)->first();
        if ($lang) {
            return $lang->name;
        }
    }
                
    /** Gets list of languages
     * 
     * @return Array [1=>'Vepsian',..]
     */
    public static function getList($without=[])
    {     
        
        $languages = self::orderBy('sequence_number')->get();
        
        $list = array();
        foreach ($languages as $row) {
            if (!in_array($row->id, $without)) {
                $list[$row->id] = $row->name;
            }
        }
        
        return $list;         
    }
}
