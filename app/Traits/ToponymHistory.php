<?php namespace App\Traits;

use \Venturecraft\Revisionable\Revision;
use App\Models\Dict\Toponym;

trait ToponymHistory
{
    public static function lastCreated($limit='') {
        $objs = self::latest();
        if ($limit) {
            $objs = $objs->take($limit);
        }
        $objs = $objs->get();
        foreach ($objs as $obj) {
            $revision = Revision::where('revisionable_type','like','%Toponym')
                                ->where('key','created_at')
                                ->where('revisionable_id',$obj->id)
                                ->latest()->first();
            if ($revision) {
                $obj->user = User::getNameByID($revision->user_id);
            }
        }
        return $objs;
    }
    
    public static function lastUpdated($limit='',$is_grouped=0) {
        $revisions = Revision::where('revisionable_type','like','%Toponym')
                            ->where('key','updated_at')
                            ->groupBy('revisionable_id')
                            ->latest()->take($limit)->get();
        $objs = [];
        foreach ($revisions as $revision) {
            $obj = Lemma::find($revision->revisionable_id);
            if ($obj) {
                $obj->user = User::getNameByID($revision->user_id);
                if ($is_grouped) {
                    $updated_date = $obj->updated_at->formatLocalized(trans('main.date_format'));            
                    $objs[$updated_date][] = $obj;
                } else {
                    $objs[] = $obj;
                }
            }
        }        
        return $objs;
    }
    
    public function allHistory() {
        $all_history = $this->revisionHistory->filter(function ($item) {
                            return $item['key'] != 'updated_at'
                                 && !($item['key'] == 'reflexive' && $item['old_value'] == null && $item['new_value'] == 0);
                        });
        foreach ($all_history as $history) {
            $history->what_created = trans('history.toponym_a');
        }
/*        foreach ($this->meanings as $meaning) {
            foreach ($meaning->revisionHistory as $history) {
                $history->what_created = trans('history.meaning_accusative', ['num'=>$meaning->meaning_n]);
            }
            $all_history = $all_history -> merge($meaning->revisionHistory);
            foreach($meaning->meaningTexts as $meaning_text) {
               foreach ($meaning_text->revisionHistory as $history) {
                   $lang = $meaning_text->lang->name;
                   $fieldName = $history->fieldName();
                   $history->field_name = trans('history.'.$fieldName.'_accusative'). ' '
                           . trans('history.meaning_genetiv',['num'=>$meaning->meaning_n])
                           . " ($lang)";
               }
               $all_history = $all_history -> merge($meaning_text->revisionHistory);
            }
        }
         
        $all_history = $all_history->sortByDesc('id')
                      ->groupBy(function ($item, $key) {
                            return (string)$item['updated_at'];
                        });*/
//dd($all_history);                        
        return $all_history;
    }
}