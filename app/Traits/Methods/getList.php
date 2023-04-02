<?php namespace App\Traits\Methods;

trait getList
{
    /** Gets list of objects
     * 
     * @return Array [1=>'Object name',..]
     */
    public static function getList($short=false)
    {     
        $locale = app()->getLocale();
        $field_name = $short ? 'short' : 'name';
        
        $objects = self::orderBy($field_name.'_'.$locale)->get();
        
        $list = array();
        foreach ($objects as $row) {
            $list[$row->id] = $row->{$field_name};
        }
        
        return $list;         
    }
}