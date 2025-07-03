<?php namespace App\Traits\Modify;

use App\Models\Dict\Wrongname;
use App\Models\Dict\Topname;

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
        
        foreach ((array)$request->topnames as $t_id => $t_info) {
            $topname = Topname::find($t_id);
            if (!$t_info['n']) {
                $topname->delete();
            } else {
                $topname->updateData($t_info); 
            }
        }
        
        foreach ((array)$request->wrongnames as $t_id => $t_info) {
            $wrongname = Wrongname::find($t_id);
            if (!$t_info['n']) {
                $wrongname->delete();
            } else {
                $wrongname->updateData($t_info); 
            }
        }
        
        foreach ((array)$request->source_toponym as $st_id => $st_data) {
            SourceToponym::find($st_id)->updateData($st_data);
        }
        foreach ((array)$request->events as $e_id => $e_data) {

            $event = Event::find($e_id);
            $event -> updateData($e_data);
        }
        
        $this->updateAddInfo($data, $request);        
    }
    
    public function updateAddInfo(array $data, $request) {
        $this->updateSettlements(remove_empty($request->settlement_id ?? []));
//        $this->settlements()->sync(!empty($request->settlement_id) ? remove_empty($request->settlement_id) : []);
        
        foreach ((array)$request->new_topname as $t_info) {
            Topname::storeData($this->id, $t_info);
        }
        
        foreach ((array)$request->new_wrongname as $t_info) {
            Wrongname::storeData($this->id, $t_info);
        }

        foreach ((array)$request->new_source_toponym as $i => $st_data) {
            SourceToponym::storeData($this->id, $st_data);
        }
        
        foreach ((array)$request->new_events as $new_event) {
            Event::storeData($this->id, $new_event);
        }

        $structs = array_filter((array)$request->structs, 'strlen');        
        $this->structs()->sync($structs);   
        
        if ($data['text_ids']) {
            $this->texts()->sync(preg_split('/;\s*/', $data['text_ids']));
        } else {
            $this->texts()->detach();
        }   
        $this->logTouch();
    }
    
    public function updateSettlements(array $new_ids) {
        $old_ids = $this->settlements()->pluck('id')->toArray();
        $this->settlements()->sync($new_ids);
        $this->logRelationRevision('settlement_id', $old_ids, $new_ids);
    }
    
    public function remove() {
        $this->structs()->detach();
        $this->settlements()->detach();
        foreach ($this->sourceToponyms as $st) {
            $st->delete();
        } 
        foreach ($this->events as $event) {
            $event->remove();
        } 
        $this->delete();
    }
    
}