<?php

namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    protected $fillable = ['toponym_id','date']; //'informant_id',
    public $timestamps = false;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Informants;
    use \App\Traits\Relations\BelongsToMany\Recorders;
    use \App\Traits\Relations\BelongsToMany\Settlements;
    use \App\Traits\Relations\BelongsToMany\Settlements1926;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function settlementsValue()
    {
        return $this->settlements ? $this->settlements()->pluck('id')->toArray() : '';
    }
    
    public function settlements1926Value()
    {
        return $this->settlements1926 ? $this->settlements1926()->pluck('id')->toArray() : '';
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
        if (!empty($this->settlements)) {
            $out[] = $this->settlementsToString();
        }
        if (!empty($this->settlements1926)) {
            $out[] = $this->settlements1926ToString();
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

    public function settlements1926ToString()
    {
        $out = [];
        foreach ($this->settlements1926 as $settlement) {
            $out[] = $settlement->name;
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
        
        if (empty($data['settlements']) && empty($data['settlements1926']) 
                && empty($data['informants']) && empty($data['recorders'])) {
            $this->remove();
            return;
        }
        
        $this->date = $data['date'];
        $this->save();

        $this->updateAddData($data);
    }
    
    public static function storeData(int $toponym_id, $data) {
        
        if (empty($data['settlements']) && empty($data['settlements1926']) 
                && empty($data['informants']) && empty($data['recorders'])) {
            return;
        }
        
        $event = Event::create(['toponym_id' => $toponym_id, 
                                 'date' => $data['date']]);         
        $event->updateAddData($data);
    }
    
    public function updateAddData($data) {
//dd($data['settlements1926']);        
        $this->settlements()->sync(remove_empty_items($data['settlements'] ?? []));
        $this->settlements1926()->sync(remove_empty_items($data['settlements1926'] ?? []));
        $this->informants()->sync(remove_empty_items($data['informants'] ?? []));
        $this->recorders()->sync(remove_empty_items($data['recorders'] ?? []));
    }
    public function remove() {
        $this->settlements()->detach();
        $this->settlements1926()->detach();
        $this->informants()->detach();
        $this->recorders()->detach();        
        $this->delete();
    }
}
