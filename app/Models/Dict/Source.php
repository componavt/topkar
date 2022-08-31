<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id', 'mention', 'source','is_map'];
    public $timestamps = false;
    
/*    public function updateData(string $source) {
        $this->name = to_right_form($name);
        $this->name_for_search = to_search_form($this->name);
        $this->save();
    }
    
    public static function storeData(int $toponym_id, $name) {
            $name = to_right_form($name);
            if (!$name) {
                return;
            }
            Topname::create(['toponym_id' => $toponym_id, 
                             'name' => $name,
                             'name_for_search' => to_search_form($name)]);         
    }*/
}
