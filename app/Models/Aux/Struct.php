<?php

namespace App\Models\Aux;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Toponym;
use App\Models\Aux\Structhier;

class Struct extends Model
{
    use HasFactory;
    use \App\Traits\Methods\getNameAttribute;
    
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
