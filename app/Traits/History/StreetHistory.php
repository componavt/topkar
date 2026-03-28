<?php

namespace App\Traits\History;

use \Venturecraft\Revisionable\Revision;

use App\Models\User;

use App\Models\Misc\Geotype;

trait StreetHistory
{
    public function allHistory()
    {
        $modelMap = [
            'geotype_id'          => Geotype::class,
        ];

        // Поля которые показываем как есть (уже человекочитаемые строки)
        $plainFields = ['struct'];

        $all_history = $this->revisionHistory->filter(function ($item) {
            return $item['key'] != 'updated_at'
                && $item['key'] != 'name_for_search_ru'
                && $item['key'] != 'name_for_search_krl'
                && $item['key'] != 'name_for_search_fi';
        });

        foreach ($all_history as $history) {
            $history->what_created = trans('history.toponym_a');

            if (isset($modelMap[$history->key])) {
                $class = $modelMap[$history->key];
                $history->old_value = $class::getNameById($history->old_value);
                $history->new_value = $class::getNameById($history->new_value);
            }
        }

        // Добавляем историю геометрии
        $geometry = $this->geometry; // hasOne StreetGeometry
        if ($geometry) {
            $geoHistory = $geometry->revisionHistory->filter(function ($item) {
                return $item['key'] === 'geojson';
            })->map(function ($item) {
                $item->what_created = trans('history.toponym_a');
                $item->key = 'geometry';
                $item->old_value = $item->old_value ? trans('history.geometry_existed') : null;
                $item->new_value = trans('history.geometry_updated');
                return $item;
            });

            $all_history = $all_history->merge($geoHistory);
        }

        $all_history = $all_history->sortByDesc('id')
            ->groupBy(function ($item, $key) {
                return (string)$item['updated_at'];
            });
        //dd($all_history);
        return $all_history;
    }

    public function structsForHistory()
    {
        return $this->structs()->with('structhier')->get()->map(function ($t) {
            $structhier = optional($t->structhier)->nameToString() ?? '—';
            return "{$t->name} ({$structhier})";
        })->toArray();
    }
}
