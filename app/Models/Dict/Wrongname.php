<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wrongname extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id', 'name', 'name_for_search', 'lang_id'];
    public $timestamps = false;
    
    // Belongs To One Relations
    use \App\Traits\Relations\BelongsTo\Lang;
    
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