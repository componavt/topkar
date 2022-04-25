<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Region;

class District1926 extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts1926';
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    use \App\Traits\Relations\BelongsTo\Region;
}
