<?php namespace App\Traits\Methods;

trait getNameByID
{
    /** Gets name of this object by code, takes into account locale.
     * 
     * @return String
     */
    public static function getNameByID($id) : String
    {
        $obj = self::find($id);
        return $obj ? $obj->name : '';
    }
                
}