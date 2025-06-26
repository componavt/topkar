<?php

namespace App\Http\Controllers\Library;

use Illuminate\Http\Request;

//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Venturecraft\Revisionable\Revision;
use Carbon\Carbon;
use DB;

use App\Models\User;
use App\Models\Dict\Lang;
use App\Models\Dict\Settlement;
use App\Models\Dict\Toponym;

class StatsController extends Controller
{
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
        $models = [
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
        $max_date = $request->input('max_date');

        if (empty($min_date) || empty($max_date)) {
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
/*            if (!empty($history_created[$modelClass])) {
                $history_updated[$label] -= $history_created[$modelClass]->count;
            }*/
        }   
        
        $quarter_query = '?min_date='.Carbon::now()->startOfQuarter()->format('Y-m-d')
                       . '&max_date='. Carbon::now()->format('Y-m-d');
        $year_query = '?min_date='.Carbon::now()->startOfYear()->format('Y-m-d')
                       . '&max_date='. Carbon::now()->format('Y-m-d');
        
        
        return view('stats.by_editor', 
                compact('history_created', 'history_updated', 'max_date', 
                        'min_date', 'models', 'quarter_query', 'year_query', 'user'));
    }
}
