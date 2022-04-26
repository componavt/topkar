<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['region_id','name_en','name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    use \App\Traits\Relations\BelongsTo\Region;
}
