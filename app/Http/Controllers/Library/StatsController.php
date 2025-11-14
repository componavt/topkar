<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Venturecraft\Revisionable\Revision;
use Carbon\Carbon;
use DB;

use App\Library\Stats;
use App\Models\User;
use App\Models\Dict\Lang;
use App\Models\Dict\Settlement;
use App\Models\Dict\Toponym;

class StatsController extends Controller
{
    public $statsService=null;
    
    public function __construct(Request $request) {
        $this->statsService = new Stats(); 
    }
    
    public function index()
    {
        $total_toponyms = Toponym::count();
        $total_toponyms_with_coords = Toponym::whereNotNull('latitude')
                                             ->whereNotNull('longitude')->count();
        $total_langs=[];
        foreach (trans('stats.langs') as $code=>$name) {
            $lang = Lang::whereCode($code)->first();
            $total_langs[$name] = ['id'=>$lang->id,
                'count'=>Toponym::whereLangId($lang->id)->count()];
        }
        
        $total_toponyms_with_etymology = Toponym::whereNotNull('etymology')->count();
        
        $total_settlements_with_toponyms 
                = Settlement::whereIn('id', function($q) {
                    $q -> select('settlement_id')->from('settlement_toponym');
                  })->count();
        
        $total_toponyms_with_sources 
                = Toponym::whereIn('id', function($q) {
                    $q -> select('toponym_id')->from('source_toponym');
                  })->count();
        
        $total_wd = Toponym::whereNotNull('wd')->count();    
        
        $total_toponyms_with_legends = Toponym::where(function($q) {
                    $q->whereNotNull('legend')
                      ->orWhereIn('id', function($q2) {
                          $q2->select('toponym_id')->from('text_toponym');
                      });
                })->count();        

        return view('stats.index',
                compact('total_langs', 'total_settlements_with_toponyms', 
                        'total_toponyms', 'total_toponyms_with_legends', 
                        'total_toponyms_with_coords', 'total_toponyms_with_sources', 
                        'total_toponyms_with_etymology', 'total_wd'));
    }
    
    public function byEditors()
    {
        // Получаем агрегированные данные по ревизиям
        $revisionStats = Revision::query()
            ->select('user_id', DB::raw('COUNT(*) as revisions_count'), DB::raw('MAX(updated_at) as last_time'))
            ->whereNotNull('user_id') // на всякий случай, если есть null
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Получаем всех пользователей, которые что-то редактировали
        $users = User::whereIn('id', $revisionStats->keys())->get();

        // Собираем итоговую коллекцию
        $editors = $users->map(function ($user) use ($revisionStats) {
            $stats = $revisionStats[$user->id];
            $user->count = number_format($stats->revisions_count, 0, ',', ' ');
            $user->last_time = $stats->last_time;
            return $user;
        })->sortByDesc('last_time');
        //dd($editors);        
        return view('stats.by_editors', 
                compact('editors'));
    }
    
    public function byEditor(User $user, Request $request)
    {
        $models = $this->statsService->getModelsList();
        
/*        $models = [
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
        // Проверка дат
        $min_date = $request->input('min_date');
        $max_date = $request->input('max_date'); */
        
        list($minDate, $maxDate, $min_date, $max_date) 
                = $this->statsService->getMinMaxDates($request, $user->id);
        
        $in_detail = $request->input('in_detail');

        $history_created = $this->statsService->getCreatedStats($user->id, $minDate, $maxDate);

        $detailed_created = [];
        if (!empty($in_detail) && $in_detail == 1) {
            $detailed_created = $this->statsService->getDetailedCreatedStats($user->id, $minDate, $maxDate);
        }

        $history_updated = $this->statsService->getUpdatedStats($user->id, $minDate, $maxDate);

        $detailed_updated = [];
        if (!empty($in_detail) && $in_detail == 1) {
            $detailed_updated = $this->statsService->getDetailedUpdatedStats($user->id, $minDate, $maxDate);
        }
//dd($detailed_updated);
/*        if (empty($min_date) || empty($max_date)) {
            $rec = Revision::where('user_id',$user->id)
                     ->selectRaw('min(created_at) as min, max(created_at) as max')
                     ->first();
            
            if (!$rec) { return;}
            
            $min_date = Carbon::parse($rec->min)->toDateString();
            $max_date = Carbon::parse($rec->max)->toDateString();
        }

        $minDate = Carbon::parse($min_date)->startOfDay();
        $maxDate = Carbon::parse($max_date)->endOfDay();
        
        $history_created = Revision::where('user_id',$user->id)
                     ->whereBetween('updated_at', [$minDate, $maxDate])
                     ->where('key', 'created_at')
                     ->whereIn('revisionable_type', array_keys($models))
                     ->groupBy('revisionable_type')
//                     ->orderBy('revisionable_type')
                     ->select('revisionable_type', DB::raw('count(*) as count'))
                     ->get()
                     ->keyBy('revisionable_type');
        
        $detailed_created = [];
        if (!empty($in_detail) && $in_detail == 1) {
            foreach ($models as $modelClass => $label) {
                if (!isset($history_created[$modelClass])) {
//                    \Log::debug("Skipping model {$modelClass}, not in history_created");            
                    continue;
                }
//        \Log::debug("Processing model {$modelClass} for detail view");

                // Получаем последние 100 (или все, если нужно) записей для данного типа модели
                $records = Revision::where('user_id', $user->id)
                    ->whereBetween('updated_at', [$minDate, $maxDate])
                    ->where('key', 'created_at')
                    ->where('revisionable_type', $modelClass)
                    ->orderBy('updated_at', 'desc') // Сначала новые
//                    ->take(100) // Ограничение, чтобы не перегружать страницу
                    ->get();
//        \Log::debug("Found " . $records->count() . " revision records for {$modelClass}");

                $detailed_list = [];
                foreach ($records as $record) {
//            \Log::debug("Processing revision ID: {$record->id}, revisionable_id: {$record->revisionable_id}");
                    try {
                        $modelInstance = $modelClass::find($record->revisionable_id);
                        if (!$modelInstance) {
//                            \Log::warning("Model instance not found for ID: {$record->revisionable_id} of type {$modelClass}");
                            $created_at = Carbon::parse($record->updated_at);
                            $date_key = $created_at->toDateString();
                            $time = $created_at->format('H:i');

                            // Для удалённого объекта
                            $name = "объект {$record->revisionable_id} удален";
                            $url = null; // Нет URL
                            $type = 'N/A'; // Или $label, если тип важен

                            // Группируем по дате
                            if (!isset($detailed_list[$date_key])) {
                                $detailed_list[$date_key] = [
                                   'formatted_date' => $created_at->translatedFormat('d F Y'),
                                   'items' => []
                                ];
                            }

                            $detailed_list[$date_key]['items'][] = [
                                'time' => $time,
                                'name' => $name,
                                'url' => $url, // null
                                'type' => $type,
                            ];

                           continue; // Объект удален или не существует
                        }
//                \Log::debug("Found model instance for ID: {$record->revisionable_id}, name: " . ($modelInstance->name ?? 'NO NAME'));

                        // Формируем строку для вывода: время, название, ссылка
                        $created_at = Carbon::parse($record->updated_at); // Используем $updated_at как дату создания
                        $date_key = $created_at->toDateString(); // Ключ для группировки: 'Y-m-d'
                        $time = $created_at->format('H:i');

                        // Группируем по дате
                        if (!isset($detailed_list[$date_key])) {
                            $detailed_list[$date_key] = [
                                'formatted_date' => $created_at->translatedFormat('d F Y'), // или Carbon::parse($date_key)->format('d.m.Y')
                                'items' => []
                            ];
                        }
                        
                        $detailed_list[$date_key]['items'][] = [
                            'time' => $time,
                            'name' => $modelInstance->name ?? 'Без названия',
                            'url' => route(plural_from_model($modelClass, true) . '.show', $modelInstance->id),
                            'type' => optional($modelInstance->geotype)->name, 
                        ];
        //dd($detailed_list);
                    } catch (\Exception $e) {
                        \Log::error("Error processing revision ID {$record->id}: " . $e->getMessage());
                        continue; // Пропускаем ошибочную запись
                    }
                }
                // Сортировка по дате (новые сверху)
                krsort($detailed_list);
//dd($detailed_list);
//        \Log::debug("Detailed list count for {$modelClass}: " . count($detailed_list));


                if (!empty($detailed_list)) {
//            \Log::debug("Added detailed list for label: {$label}");
                    $detailed_created[$label] = $detailed_list;
                } else {
            \Log::debug("No detailed list added for label: {$label} (empty list)");
                }
            }
        }
//dd($detailed_created);
        $history_updated = [];        
        foreach ($models as $modelClass => $label) {
            $count = Revision::where('user_id', $user->id)
                ->whereBetween('updated_at', [$minDate, $maxDate])
                ->where('revisionable_type', $modelClass)
                ->where('key', '<>', 'created_at')
                ->whereNotIn('revisionable_id', function ($q) use ($user, $minDate, $maxDate, $modelClass) {
                    $q->select('revisionable_id')->from('revisions')
                      ->where('user_id',$user->id)
                     ->whereBetween('updated_at', [$minDate, $maxDate])
                     ->where('key', 'created_at')
                     ->where('revisionable_type', $modelClass);
                })
                ->distinct('revisionable_id')
                ->count('revisionable_id'); // только уникальные ID

            $history_updated[$label] = $count;

        }   
*/        
        $quarter_query = '?min_date='.Carbon::now()->startOfQuarter()->format('Y-m-d')
                       . '&max_date='. Carbon::now()->format('Y-m-d');
        $year_query = '?min_date='.Carbon::now()->startOfYear()->format('Y-m-d')
                       . '&max_date='. Carbon::now()->format('Y-m-d');
        
        
        return view('stats.by_editor', 
                compact('detailed_created', 'detailed_updated', 'history_created', 'history_updated', 
                        'in_detail', 'max_date', 'min_date', 'models', 
                        'quarter_query', 'year_query', 'user'));
    }
}
