<?php
namespace App\Models\Misc;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use App\Models\Dict\Toponym;

class SourceToponym extends Model
{
//    use HasFactory;
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = false; // Don't remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 999999; // Stop tracking revisions after 999999 changes have been made.
    protected $revisionFormattedFields = array(
        'updated_at' => 'datetime:m/d/Y g:i A'
    );
    
    protected $table = 'source_toponym';
    protected $fillable = ['toponym_id', 'mention', 'source_id', 'source_text', 'sequence_number'];
    public $timestamps = false;
    
    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Source;
    use \App\Traits\Relations\BelongsTo\Toponym;
    
    public static function boot()
    {
        parent::boot();
    }
    
    public function sourceToString($short=false) {
        if (!$this->source && !$this->source_text) {
            return;
        }
        $out = [];
        if ($this->source) {
            $out[] = $short ? $this->source->short : $this->source->name;
        }
        if ($this->source_text) {
            $out[] = $this->source_text;
        }
        return join(', ', $out); 
    }


    public function countToponyms() {
        $search_source=$this->source_text;
        return Toponym::whereIn('id', function ($q) use ($search_source){
                            $q->select('toponym_id')->from('sources')
                              ->where('source_text',$search_source);
                        })->count();
    }
    public function updateData($data) {
        if (!$data['source_id'] && !$data['source_text']) {
            $this->delete();
            return;
        }
        
        $this->mention = $data['mention'];
        $this->source_id = $data['source_id'];
        $this->source_text = $data['source_text'] ?? '';
        $this->sequence_number = $data['sequence_number'];
        $this->save();
    }
    
    public static function storeData(int $toponym_id, $data) {
        if (!$data['source_id'] && !$data['source_text']) {
            return;
        }
        self::create(['toponym_id' => $toponym_id, 
                        'mention' => $data['mention'],
                        'source_id' => $data['source_id'] ?? null,
                        'source_text' => $data['source_text'] ?? '',
                        'sequence_number' => $data['sequence_number']]);         
    }
}