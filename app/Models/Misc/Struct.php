<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Toponym;
use App\Models\Misc\Structhier;

class Struct extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name_ru',  'name_en', 'structhier_id'];
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    /**
     * The toponyms that belong to the structure. (many to many relation)
     */
    public function toponyms()
    {
        return $this->belongsToMany(Toponym::class);
    }
    
    public function structhier()
    {
        return $this->belongsTo(Structhier::class);
    }
}
