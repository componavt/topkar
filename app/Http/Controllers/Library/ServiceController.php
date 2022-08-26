<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

use App\Models\Dict\Topname;
use App\Models\Dict\Toponym;

class ServiceController extends Controller
{
    // service/tmp_fill_name_for_search
    public function tmp_fill_name_for_search() {
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
        $toponyms = Toponym::whereNull('name_for_search')
//                ->take(10)
                ->orderBy('id')->get();
//dd($toponyms);  
        foreach ($toponyms as $toponym) {
            $right_name = to_right_form($toponym->name);
            if ($right_name != $toponym->name) {
                $toponym->name = $right_name;
            }
            
            $toponym->name_for_search = to_search_form($right_name); 
            $toponym->save();            
       }
print 'done.';       
    }
    
    // service/tmp_fill_topnames_from_variants
    public function tmp_fill_topnames_from_variants() {
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
        $toponyms = Toponym::whereNotNull('VARIANTS')               
//                ->select('id', 'VARIANTS')
//                ->take(10)
                ->orderBy('id')->get();
//dd($toponyms);  
        foreach ($toponyms as $toponym) {
            foreach(preg_split("/\s*,\s*/", $toponym->VARIANTS) as $name) {
                $name = to_right_form($name);
                if (!$toponym->topnames || !$toponym->topnames()->where('name', $name)->first()) {
                    Topname::create([
                        'toponym_id'=>$toponym->id,
                        'name'=>$name, 
                        'name_for_search'=>to_search_form($name)
                    ]);
                }
            }
        }
print 'done.';       
    }
}
