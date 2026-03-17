<?php

namespace App\Traits\Methods;

trait getList
{
    /** Gets list of objects
     *
     * @return Array [1=>'Object name',..]
     */
    public static function getList($short = false, $structhier_id = null)
    {
        $locale = app()->getLocale();
        $field_name = $short ? 'short' : 'name';

        $objects = self::orderBy($field_name . '_' . $locale)
            ->when($structhier_id, function ($query) use ($structhier_id) {
                $query->whereHas('structhier', function ($query) use ($structhier_id) {
                    $query->where('id', $structhier_id);
                });
            })->get();

        $list = array();
        foreach ($objects as $row) {
            $list[$row->id] = $row->{$field_name};
        }

        return $list;
    }
}
