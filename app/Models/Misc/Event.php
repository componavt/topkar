<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id','date']; //'informant_id',
    public $timestamps = false;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Informants;
    use \App\Traits\Relations\BelongsToMany\Recorders;
    use \App\Traits\Relations\BelongsToMany\Settlements;
    
    public function settlementsValue()
    {
        return $this->settlements ? $this->settlements()->pluck('id')->toArray() : '';
    }
    
    public function informantsValue()
    {
        return $this->informants ? $this->informants()->pluck('id')->toArray() : '';
    }
    
    public function recordersValue()
    {
        return $this->recorders ? $this->recorders()->pluck('id')->toArray() : '';
    }

    public function eventString()
    {
        $out = [];
        if ($this->settlements && $this->settlements()->count()) {
            $out[] = $this->settlementsToString();
        }
        if ($this->date) {
            $out[] = $this->date;
        }
        if ($this->informants && $this->informants()->count()) {
            $out[] = $this->informantsToString();
        }
        if ($this->recorders && $this->recorders()->count()) {
            $out[] = trans('misc.recorded_by').' '.$this->recordersToString();
        }
        return join('; ', $out);
    }
    
    public function settlementsToString()
    {
        $out = [];
        foreach ($this->settlements as $settlement) {
            $out[] = $settlement->settlementString('', false);
        }
        return join(', ', $out);
    }

    public function informantsToString()
    {
        $out = [];
        foreach ($this->informants as $informant) {
            $out[] = $informant->informantString();
        }
        return join(', ', $out);
    }

    public function recordersToString()
    {
        $locale = app()->getLocale();
        return join(', ', $this->recorders()->pluck('name_'.$locale)->toArray());
    }

    public function updateData($data) {
        
        if ((!isset($data['settlements']) || !sizeof($data['settlements'])) 
                && (!isset($data['informants']) || !sizeof($data['informants'])) 
                && (!isset($data['recorders']) || !sizeof($data['recorders']))) {
            $this->remove();
            return;
        }
        
        $this->date = $data['date'];
        $this->save();

        $this->settlements()->sync($data['settlements'] ?? []);
        $this->informants()->sync($data['informants'] ?? []);
        $this->recorders()->sync($data['recorders'] ?? []);
    }
    
    public static function storeData(int $toponym_id, $data) {
        
        if ((!isset($data['settlements']) || !sizeof($data['settlements'])) 
                && (!isset($data['informants']) || !sizeof($data['informants'])) 
                && (!isset($data['recorders']) || !sizeof($data['recorders']))) {
            return;
        }
        
        $event = Event::create(['toponym_id' => $toponym_id, 
                                 'date' => $data['date']]);         
        $event->settlements()->sync($data['settlements'] ?? []);
        $event->informants()->sync($data['informants'] ?? []);
        $event->recorders()->sync($data['recorders'] ?? []);
    }
    
    public function remove() {
        $this->settlements()->detach();
        $this->informants()->detach();
        $this->recorders()->detach();        
        $this->delete();
    }
}
