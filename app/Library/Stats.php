<?php

namespace App\Library;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\Revision;

class Stats
{
    protected $models = [
        'App\Models\Dict\Toponym'  => 'топонимов',
        'App\Models\Dict\District' => 'районов',
        'App\Models\Dict\District1926' => 'районов нач. XX в.',
        'App\Models\Dict\Selsovet1926' => 'сельсоветов нач. XX в.',
        'App\Models\Dict\Settlement' => 'поселений',
        'App\Models\Dict\Settlement1926' => 'поселений нач. XX в.',
        'App\Models\Misc\Geotype'  => 'видов объектов',
        'App\Models\Misc\Informant'  => 'информантов',
        'App\Models\Misc\Recorder'  => 'собирателей',
        'App\Models\Misc\Source'  => 'источников',
        'App\Models\Misc\Struct'  => 'структур',
    ];

    public function getModelsList(): array
    {
        return $this->models;
    }

    /**
     * Получить даты min и max из запроса или определить автоматически.
     * @return array [Carbon $minDate, Carbon $maxDate]
     */
    public function getMinMaxDates(Request $request, $userId): array
    {
        $min_date = $request->input('min_date');
        $max_date = $request->input('max_date');

        if (empty($min_date) || empty($max_date)) {
            $rec = Revision::where('user_id', $userId)
                     ->selectRaw('min(created_at) as min, max(created_at) as max')
                     ->first();

            if (!$rec) {
                // Если нет записей, вернуть сегодня
                $min_date = Carbon::today()->toDateString();
                $max_date = Carbon::today()->toDateString();
            } else {
                $min_date = Carbon::parse($rec->min)->toDateString();
                $max_date = Carbon::parse($rec->max)->toDateString();
            }
        }

        $minDate = Carbon::parse($min_date)->startOfDay();
        $maxDate = Carbon::parse($max_date)->endOfDay();

        return [$minDate, $maxDate, $min_date, $max_date];
    }
    
    /**
     * Получить статистику по созданным объектам.
     */
    public function getCreatedStats($userId, Carbon $minDate, Carbon $maxDate): \Illuminate\Support\Collection
    {
        return Revision::where('user_id', $userId)
            ->whereBetween('updated_at', [$minDate, $maxDate])
            ->where('key', 'created_at')
            ->whereIn('revisionable_type', array_keys($this->models))
            ->groupBy('revisionable_type')
            ->select('revisionable_type', DB::raw('count(*) as count'))
            ->get()
            ->keyBy('revisionable_type');
    }

    /**
     * Получить детализированную статистику по созданным объектам.
     */
    public function getDetailedCreatedStats($userId, Carbon $minDate, Carbon $maxDate): array
    {
        $detailed_created = [];

        foreach ($this->models as $modelClass => $label) {
            $records = Revision::where('user_id', $userId)
                ->whereBetween('updated_at', [$minDate, $maxDate])
                ->where('key', 'created_at')
                ->where('revisionable_type', $modelClass)
                ->orderBy('updated_at', 'desc')
                ->get();

            if ($records->isEmpty()) {
                continue;
            }

            $detailed_list = [];
            foreach ($records as $record) {
                try {
                    $modelInstance = $modelClass::find($record->revisionable_id);

                    $created_at = Carbon::parse($record->updated_at);
                    $date_key = $created_at->toDateString();
                    $time = $created_at->format('H:i');

                    if (!$modelInstance) {
                        // Объект удален
                        $name = "объект {$record->revisionable_id} удален";
                        $url = null;
                        $type = 'N/A';
                    } else {
                        // Объект существует
                        $name = $modelInstance->name ?? 'Без названия';
                        $url = route(plural_from_model($modelClass, true) . '.show', $modelInstance->id);
                        $type = optional($modelInstance->geotype)->name ?? 'N/A';
                    }

                    if (!isset($detailed_list[$date_key])) {
                        $detailed_list[$date_key] = [
                            'formatted_date' => $created_at->translatedFormat('d F Y'),
                            'items' => []
                        ];
                    }

                    $detailed_list[$date_key]['items'][] = [
                        'time' => $time,
                        'name' => $name,
                        'url' => $url,
                        'type' => $type,
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing revision ID {$record->id}: " . $e->getMessage());
                    continue;
                }
            }

            krsort($detailed_list);

            if (!empty($detailed_list)) {
                $detailed_created[$label] = $detailed_list;
            }
        }

        return $detailed_created;
    }

    /**
     * Получить статистику по изменённым объектам (без учёта созданных в тот же период).
     */
    public function getUpdatedStats($userId, Carbon $minDate, Carbon $maxDate): array
    {
        $history_updated = [];

        foreach ($this->models as $modelClass => $label) {
            $count = Revision::where('user_id', $userId)
                ->whereBetween('updated_at', [$minDate, $maxDate])
                ->where('revisionable_type', $modelClass)
                ->where('key', '<>', 'created_at')
                ->whereNotIn('revisionable_id', function ($q) use ($userId, $minDate, $maxDate, $modelClass) {
                    $q->select('revisionable_id')->from('revisions')
                      ->where('user_id', $userId)
                      ->whereBetween('updated_at', [$minDate, $maxDate])
                      ->where('key', 'created_at')
                      ->where('revisionable_type', $modelClass);
                })
                ->distinct('revisionable_id')
                ->count('revisionable_id');

            $history_updated[$label] = $count;
        }

        return $history_updated;
    }

    /**
     * Получить детализированную статистику по изменённым объектам.
     */
    public function getDetailedUpdatedStats($userId, Carbon $minDate, Carbon $maxDate): array
    {
        $detailed_updated = [];

        foreach ($this->models as $modelClass => $label) {
            $records = Revision::where('user_id', $userId)
                ->whereBetween('updated_at', [$minDate, $maxDate])
                ->where('revisionable_type', $modelClass)
                ->where('key', '<>', 'created_at') // Только изменения
                ->whereNotIn('revisionable_id', function ($q) use ($userId, $minDate, $maxDate, $modelClass) {
                    $q->select('revisionable_id')->from('revisions')
                      ->where('user_id', $userId)
                      ->whereBetween('updated_at', [$minDate, $maxDate])
                      ->where('key', 'created_at')
                      ->where('revisionable_type', $modelClass);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            if ($records->isEmpty()) {
                continue;
            }

            $detailed_list = [];
            foreach ($records as $record) {
                try {
                    // Получаем список изменённых полей
                    $changes = json_decode($record->old_value, true) ?: [];
                    $newValues = json_decode($record->new_value, true) ?: [];
                    $changed_fields = [];

                    foreach ($newValues as $key => $value) {
                        if (isset($changes[$key]) && $changes[$key] !== $value) {
                            $changed_fields[] = $key;
                        }
                    }

                    $updated_at = Carbon::parse($record->updated_at);
                    $date_key = $updated_at->toDateString();
                    $time = $updated_at->format('H:i');

                    $modelInstance = $modelClass::find($record->revisionable_id);

                    if (!$modelInstance) {
                        // Объект удален
                        $name = "объект {$record->revisionable_id} (изменён, но удалён)";
                        $url = null;
                        $fields = 'N/A';
                    } else {
                        // Объект существует
                        $name = $modelInstance->name ?? 'Без названия';
                        $url = route(plural_from_model($modelClass, true) . '.show', $modelInstance->id);
                        $fields = !empty($changed_fields) ? implode(', ', $changed_fields) : 'Другие поля';
                    }

                    if (!isset($detailed_list[$date_key])) {
                        $detailed_list[$date_key] = [
                            'formatted_date' => $updated_at->translatedFormat('d F Y'),
                            'items' => []
                        ];
                    }

                    $detailed_list[$date_key]['items'][] = [
                        'time' => $time,
                        'name' => $name,
                        'url' => $url,
                        'fields' => $fields, // Показываем изменённые поля
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing updated revision ID {$record->id}: " . $e->getMessage());
                    continue;
                }
            }

            krsort($detailed_list);

            if (!empty($detailed_list)) {
                $detailed_updated[$label] = $detailed_list;
            }
        }

        return $detailed_updated;
    }
}