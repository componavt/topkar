<?php namespace App\Traits\Modify;

use App\Models\Dict\Topname;
use App\Models\Dict\Wrongname;

use App\Models\Misc\Event;
use App\Models\Misc\SourceToponym;

trait ToponymModify
{    
    public static function storeData(array $data, $request) {
        $toponym = self::create($data); 
        $toponym->name_for_search = to_search_form($toponym->name);
        $toponym->save(); 
        
        $toponym->updateAddInfo($data, $request);
        
        return $toponym;
    }
    
    public function updateData(array $data, $request) {
        $this->fill($data);
        if (!$this->district_id && $this->settlement && $this->settlement->district_id) {
            $this->district_id = $this->settlement->district_id;
        }
        $this->name_for_search = to_search_form($this->name);
        $this->save();
                       
        $this->updateAddInfo($data, $request);        
    }
    
    public function updateAddInfo(array $data, $request) {
        $this->updateSettlements(remove_empty($request->settlement_id ?? []));        
        $this->updateTopnames($request);
        $this->updateWrongnames($request);
        $this->updateSources($request);
        $this->updateEvents($request);                
        $this->updateStructs($request);        
        $this->updateTexts($data['text_ids'] ? preg_split('/;\s*/', $data['text_ids']) : []);
        
        $this->logTouch();
    }
    
    public function updateSettlements(array $new_ids) {
        $old_ids = $this->settlements()->pluck('id')->toArray();
        $this->settlements()->sync($new_ids);
        $this->logRelationRevisionIds('settlement_id', $old_ids, $new_ids);
    }
    
    public function updateTopnames($request) {
        // Сохраняем старые значения имён:
        $old_names = $this->topnamesForHistory();
        
        foreach ((array)$request->topnames as $t_id => $t_info) {
            $topname = Topname::find($t_id);
            if (!$t_info['n']) {
                $topname->delete();
            } else {
                $topname->updateData($t_info); 
            }
        }

        foreach ((array)$request->new_topname as $t_info) {
            Topname::storeData($this->id, $t_info);
        }

        // Обновляем список после всех действий:
        $new_names = $this->topnamesForHistory();

        // Логируем ревизию (если есть изменения):
        $this->logRelationRevisionIds('topname', $old_names, $new_names);
    }
    
    public function updateWrongnames($request) {
        $old_names = $this->wrongnamesForHistory();
        
        foreach ((array)$request->wrongnames as $t_id => $t_info) {
            $name = Wrongname::find($t_id);
            if (!$t_info['n']) {
                $name->delete();
            } else {
                $name->updateData($t_info); 
            }
        }

        foreach ((array)$request->new_wrongname as $t_info) {
            Wrongname::storeData($this->id, $t_info);
        }

        $this->logRelationRevisionIds('wrongname', $old_names, $this->wrongnamesForHistory());
    }
    
    public function updateStructs($request) {
        $old = $this->structsForHistory();

        $structs = array_filter((array)$request->structs, 'strlen');    // strlen удаляет пустые элементы    
        $this->structs()->sync($structs);   

        $this->logRelationRevisionIds('struct', $old, $this->structsForHistory());
    }
    
    public function updateTexts($new_ids) {
        $old_ids = $this->texts()->pluck('id')->toArray();
        $this->texts()->sync($new_ids);
        $this->logRelationRevisionIds('texts', $old_ids, $new_ids);
    }
    
    public function updateSources($request) {
        $old = $this->sourcesForHistory();
        
        foreach ((array)$request->source_toponym as $st_id => $st_data) {
            SourceToponym::find($st_id)->updateData($st_data);
        }

        foreach ((array)$request->new_source_toponym as $i => $st_data) {
            SourceToponym::storeData($this->id, $st_data);
        }
        
        $this->logRelationRevisionIds('sources', $old, $this->sourcesForHistory(), '; ');
    }
    
    public function updateEvents($request) {
        $old = $this->eventsForHistory();
        
        foreach ((array)$request->events as $e_id => $e_data) {
            $event = Event::find($e_id);
            $event -> updateData($e_data);
        }

        foreach ((array)$request->new_events as $new_event) {
            Event::storeData($this->id, $new_event);
        }
        
        $this->logRelationRevisionIds('events', $old, $this->eventsForHistory(), '; ');
    }
    
    public function remove() {
        $this->structs()->detach();
        $this->settlements()->detach();
        $this->texts()->detach();
        foreach ($this->sourceToponyms as $st) {
            $st->delete();
        } 
        foreach ($this->events as $event) {
            $event->remove();
        } 
        $this->delete();
    }
    
}