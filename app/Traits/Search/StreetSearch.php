<?php

namespace App\Traits\Search;

trait StreetSearch
{
    /**
     * Формирование аргументов URL для поиска улиц
     */
    public static function urlArgs($request)
    {
        $url_args = url_args($request) + [
            'in_desc' => (int)$request->input('in_desc'),
            'search_id' => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
            'search_name' => $request->input('search_name'),
            'search_geotypes' =>  (array)$request->input('search_geotypes'),
            'search_structs'    => (array)$request->input('search_structs'),
            'sort_by' => $request->input('sort_by'),
        ];

        $sort_list = self::SortList;
        if (!in_array($url_args['sort_by'], $sort_list)) {
            $url_args['sort_by'] = $sort_list[0];
        }

        return remove_empty_elems($url_args);
    }

    /**
     * Поиск улиц по параметрам
     */
    public static function search($url_args)
    {
        $streets = self::orderBy($url_args['sort_by'], $url_args['in_desc'] ? 'DESC' : 'ASC')
            ->orderBy('name_ru');

        $streets = self::searchByName($streets, $url_args['search_name']);
        $streets = self::searchByID($streets, $url_args['search_id']);
        $streets = self::searchByGeotype($streets, $url_args['search_geotypes']);
        $streets = self::searchByStruct($streets, $url_args['search_structs']);

        return $streets;
    }

    /**
     * Поиск по названию
     */
    public static function searchByName($streets, $search_name)
    {
        if (!$search_name) {
            return $streets;
        }

        // Добавляем % для LIKE поиска, если их нет
        if (strpos($search_name, '%') === false) {
            $search_name = '%' . $search_name . '%';
        }

        return $streets->where(function ($q) use ($search_name) {
            $q->where('name_for_search_ru', 'like', $search_name)
                ->orWhere('name_for_search_krl', 'like', $search_name)
                ->orWhere('name_for_search_fi', 'like', $search_name);
        });
    }

    /**
     * Поиск по ID
     */
    public static function searchByID($streets, $search_id)
    {
        if (!$search_id) {
            return $streets;
        }

        return $streets->where('id', $search_id);
    }

    /**
     * Поиск по типу
     */
    public static function searchByGeotype($streets, $search_geotype)
    {
        if (!isset($search_geotype) || !$search_geotype) {
            return $streets;
        }

        return $streets->where('geotype_id', $search_geotype);
    }

    public static function searchByStruct($builder, $search_structs)
    {

        if (!sizeof($search_structs)) {
            return $builder;
        }

        $builder = $builder->whereIn('id', function ($q1) use ($search_structs) {
            $q1->select('street_id')->from('street_struct');
            foreach ($search_structs as $h_id => $structs) {
                $q1->whereIn('struct_id', $structs);
            }
        });
        return $builder;
    }
}
