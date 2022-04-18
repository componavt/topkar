<?php namespace App\Traits\Methods;

trait getList
{
    /** Gets list of objects
     * 
     * @return Array [1=>'Object name',..]
     */
    public static function getList()
    {     
        $locale = app()->getLocale();
        
        $objects = self::orderBy('name_'.$locale)->get();
        
        $list = array();
        foreach ($objects as $row) {
            $list[$row->id] = $row->name;
        }
        
        return $list;         
    }
}