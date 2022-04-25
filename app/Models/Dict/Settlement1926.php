<?php

namespace App\Models\Dict;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dict\Selsovet1926;

class Settlement1926 extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settlements1926';
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    /**
     * Get the selsovet1926 which contains this settlement1926
     * One To Many (Inverse) / Belongs To
     * https://laravel.com/docs/8.x/eloquent-relationships#one-to-many-inverse
     */
    public function selsovet1926()
    {
        //                                           'foreign_key', 'owner_key'
        return $this->belongsTo(Selsovet1926::class, 'selsovet_id', 'id');
    }
}
