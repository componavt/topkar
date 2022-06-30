<?php namespace App\Traits\Methods\search;

trait byNameKRL
{
    public static function searchByName($objs, $name) {
        if (!$name) {
            return $objs;
        }
        return $objs->where(function($q) use ($name){
                        $q->where('name_en','like', $name)
                          -> orWhere('name_ru','like',$name)
                          -> oRwhere('name_krl','like',$name);
                 });
    }
}