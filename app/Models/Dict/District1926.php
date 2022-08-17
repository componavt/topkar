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
    public $timestamps = false;
    protected $fillable = ['region_id','name_en','name_ru'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    
    use \App\Traits\Relations\BelongsTo\Region;
    
    public function toponyms()
    {
        $district_id = $this->id;
        return Toponym::whereIn('settlement1926_id', function($q2) use ($district_id) {
                    $q2 -> select ('id') -> from ('settlements1926')
                        -> whereIn('selsovet_id', function($q3) use ($district_id) {
                            $q3 -> select ('id') -> from ('selsovets1926')
                                -> where('district1926_id', $district_id);
                        });
                });
    }
    
}
