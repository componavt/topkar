<?php namespace App\Traits\Methods;

trait sortList
{
    public static function sortList() {
        $list = [];
        foreach (self::SortList as $field) {
            $list[$field] = trans('messages.sort'). ' '. trans('toponym.by_'.$field);
        }
        return $list;
    }
}