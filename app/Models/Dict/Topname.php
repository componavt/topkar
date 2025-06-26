<?php

namespace App\Models\Dict;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topname extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionCreationsEnabled = true; // By default the creation of a new model is not stored as a revision. Only subsequent changes to a model is stored.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    protected $fillable = ['toponym_id', 'name', 'name_for_search', 'lang_id'];
    public $timestamps = false;
    
    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Lang;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function updateData($info) {
        $this->name = to_right_form($info['n']);
        $this->name_for_search = to_search_form($this->name);
        $this->lang_id = $info['l'] ? (int)$info['l'] : null;
        $this->save();
    }
    
    public static function storeData(int $toponym_id, $info) {
            $name = to_right_form($info['n']);
            if (!$name) {
                return;
            }
            self::create(['toponym_id' => $toponym_id, 
                             'name' => $name,
                             'name_for_search' => to_search_form($name),
                             'lang_id' => $info['l'] ? (int)$info['l'] : null]);         
    }
    
}
