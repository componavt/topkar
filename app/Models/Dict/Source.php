<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dict\Toponym;

class Source extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id', 'mention', 'source', 'sequence_number'/*,'is_map'*/];
    public $timestamps = false;
    
    public function countToponyms() {
        $search_source=$this->source;
        return Toponym::whereIn('id', function ($q) use ($search_source){
                            $q->select('toponym_id')->from('sources')
                              ->where('source',$search_source);
                        })->count();
    }
    public function updateData($data) {
        if (!$data['source']) {
            $this->delete();
            return;
        }
        
        $this->mention = $data['mention'];
        $this->source = $data['source'];
        $this->sequence_number = $data['sequence_number'];
        $this->save();
    }
    
    public static function storeData(int $toponym_id, $data) {
        if (!$data['source']) {
            return;
        }
        Source::create(['toponym_id' => $toponym_id, 
                        'mention' => $data['mention'],
                        'source' => $data['source'],
                        'sequence_number' => $data['sequence_number']]);         
    }
}
