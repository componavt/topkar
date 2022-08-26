<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topname extends Model
{
    use HasFactory;
    protected $fillable = ['toponym_id', 'name', 'name_for_search'];
    public $timestamps = false;
    
    public function updateData(string $name) {
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
    }
    
}
