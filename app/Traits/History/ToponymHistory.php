<?php namespace App\Traits\History;

use \Venturecraft\Revisionable\Revision;

use App\Models\User;

use App\Models\Dict\District;
use App\Models\Dict\Lang;
use App\Models\Dict\Settlement;
use App\Models\Dict\Settlement1926;

use App\Models\Misc\Geotype;
use App\Models\Misc\EthnosTerritory;
use App\Models\Misc\EtymologyNation;

trait ToponymHistory
{
    public static function lastCreated($limit='') {
        $toponyms = self::latest();
        if ($limit) {
            $toponyms = $toponyms->take($limit);
        }
        $toponyms = $toponyms->with('geotype')->get();
        
        $toponymIds = $toponyms->pluck('id')->all();

        // Получаем последние ревизии по созданию для всех топонимов
        $revisions = Revision::where('revisionable_type', 'like', '%Toponym')
            ->where('key', 'created_at')
            ->whereIn('revisionable_id', $toponymIds)
            ->latest()
            ->get()
            ->unique('revisionable_id')
            ->keyBy('revisionable_id');

        // Получаем id пользователей, чтобы не дёргать по одному
        $userIds = $revisions->pluck('user_id')->unique()->all();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Назначаем user-имя каждому топониму
        foreach ($toponyms as $toponym) {
            $revision = $revisions->get($toponym->id);
            if ($revision) {
                $toponym->user = $users[$revision->user_id]->full_name ?? null;
            }
        }
        
        return $toponyms;
    }
    
    public static function lastUpdated($limit='',$is_grouped=0) {
        // Получаем ревизии одним запросом
        $revisions = Revision::where('revisionable_type', 'like', '%Toponym')
            ->where('key', 'updated_at')
            ->latest()
            ->get()
            ->unique('revisionable_id')  // берём только одну ревизию на каждый топоним
            ->take($limit);

        // Собираем id топонимов и пользователей
        $toponymIds = $revisions->pluck('revisionable_id')->all();
//dd($toponymIds);        
        $userIds = $revisions->pluck('user_id')->filter()->unique()->all();

        // Загружаем топонимы и пользователей пачкой
        $toponyms = self::whereIn('id', $toponymIds)->get()->keyBy('id');
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $result = [];

        foreach ($revisions as $revision) {
            $toponym = $toponyms->get($revision->revisionable_id);
            if (!$toponym) {
                continue;
            }

            // Добавляем имя пользователя
            $toponym->user = $users[$revision->user_id]->full_name ?? null;

            if ($is_grouped) {
                $updated_date = $toponym->updated_at->formatLocalized(trans('general.date_format'));
                $result[$updated_date][] = $toponym;
            } else {
                $result[] = $toponym;
            }
        }

        return $result;
    }    

    public function allHistory() {
        $modelMap = [
            'district_id'         => District::class,
            'ethnos_territory_id' => EthnosTerritory::class,
            'etymology_nation_id' => EtymologyNation::class,
            'geotype_id'          => Geotype::class,
            'lang_id'             => Lang::class,
            'settlement1926_id'   => Settlement1926::class,
        ];   
        
        $all_history = $this->revisionHistory->filter(function ($item) {
                            return $item['key'] != 'updated_at' 
/*                                   && $item['key'] != 'text_xml'
                                   && $item['key'] != 'transtext_id'
                                   && $item['key'] != 'event_id'
                                   && $item['key'] != 'checked'
                                   && $item['key'] != 'text_structure' */
                                   && $item['key'] != 'name_for_search';
                                 //&& !($item['key'] == 'reflexive' && $item['old_value'] == null && $item['new_value'] == 0);
                        });
        foreach ($all_history as $history) {
            $history->what_created = trans('history.toponym_a');
            
            if ($history->key == 'settlement_id') {
                $history->old_value = Settlement::getNamesByIds(preg_split("/,\s*/", $history->old_value)); 
                $history->new_value = Settlement::getNamesByIds(preg_split("/,\s*/", $history->new_value)); 
            }

            if (isset($modelMap[$history->key])) {
                $class = $modelMap[$history->key];
                $history->old_value = $class::getNameById($history->old_value);
                $history->new_value = $class::getNameById($history->new_value);
            }
        }
 
/*        if ($this->transtext) {
            $transtext_history = $this->transtext->revisionHistory->filter(function ($item) {
                                return $item['key'] != 'text_xml';
                            });
            foreach ($transtext_history as $history) {
                    $history->what_created = trans('history.transtext_accusative');
                    $fieldName = $history->fieldName();
                    $history->field_name = trans('history.'.$fieldName.'_accusative')
                            . ' '. trans('history.transtext_genetiv');
                }
                $all_history = $all_history -> merge($transtext_history);
        }
        
        if ($this->event) {
            $event_history = $this->event->revisionHistory->filter(function ($item) {
                                return $item['key'] != 'text_xml';
                            });
            foreach ($event_history as $history) {
                    $fieldName = $history->fieldName();
                    $history->field_name = trans('history.'.$fieldName.'_accusative')
                            . ' '. trans('history.event_genetiv');
                }
                $all_history = $all_history -> merge($event_history);
        }
        
        if ($this->source) {
            $source_history = $this->source->revisionHistory->filter(function ($item) {
                                return $item['key'] != 'text_xml';
                            });
            foreach ($source_history as $history) {
                    $fieldName = $history->fieldName();
                    $history->field_name = trans('history.'.$fieldName.'_accusative')
                            . ' '. trans('history.source_genetiv');
                }
                $all_history = $all_history -> merge($source_history);
        }*/
         
        $all_history = $all_history->sortByDesc('id')
                      ->groupBy(function ($item, $key) {
                            return (string)$item['updated_at'];
                        });
//dd($all_history);                        
        return $all_history;
    }
    
    public function topnamesForHistory() {
        return $this->topnames()->with('lang')->get()->map(function ($t) {
            $lang = optional($t->lang)->code ?? '—';
            return "{$t->name} ({$lang})";
        })->toArray();
    }
    
    public function wrongnamesForHistory() {
        return $this->wrongnames()->with('lang')->get()->map(function ($t) {
            $lang = optional($t->lang)->code ?? '—';
            return "{$t->name} ({$lang})";
        })->toArray();
    }
    
    public function structsForHistory() {
        return $this->structs()->with('structhier')->get()->map(function ($t) {
            $structhier = optional($t->structhier)->nameToString() ?? '—'; 
            return "{$t->name} ({$structhier})";
        })->toArray();
    }
    
    public function sourcesForHistory() {
        return $this->sourceToponyms()->get()->map(function ($t) {
            return "{$t->sequence_number}. {$t->mention} // {$t->sourceToString()}";
        })->toArray();
    }
    
    public function eventsForHistory() {
        return $this->events()->get()->map(function ($t) {
            return $t->settlementsToString().(($t->settlementsToString() && $t->settlements1926ToString()) ? ',' : '')
                .$t->settlements1926ToString().(($t->date) ? ', '.$t->date : '')
                ." ({$t->informantsToString()}, {$t->recordersToString()})";
        })->toArray();
    }
}