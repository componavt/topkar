<?php

namespace App\Models\Vepkar;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
//    use HasFactory;
    public $connection = 'vepkar';
    protected $fillable = [];
    
    use \App\Traits\Methods\getNameAttribute;
    
    use \App\Traits\Relations\BelongsToMany\Toponyms;

    public static function getList()
    {     
        $recs = self::orderBy('title')->get();
        
        $list = array();
        foreach ($recs as $row) {
            $list[$row->id] = $row->title;
        }
        
        return $list;         
    }
    
}
