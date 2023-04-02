<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Misc\Source;
use App\Models\Misc\SourceToponym;

class SourceToponymController extends Controller
{
    public function index() {
        $sources = SourceToponym::groupBy('source_id', 'source_text')
                         ->selectRaw('source_id, source_text, count(*) as count')
                         ->orderBy('source_id', 'desc')
                         ->orderBy('count', 'desc')
                         ->orderBy('source_text')
                        ->get();
        return view('misc.source_toponym.index', compact('sources'));        
    }
    
    public function create(Request $request)
    {
        $num = $request->num;
        $var_name='new_source_toponym';
        $source_values = [''=>NULL] + Source::getList(true);
        return view('misc.source_toponym._create_edit', 
                compact('num', 'source_values', 'var_name'));
    }
    
// select source_text from source_toponym where source_text like 'SNA:%';    
    public function extractSources() {
/*        $sources = SourceToponym::where('source_text', 'like', 'SNA:%')
                //->take(1)
                ->get();
        foreach ($sources as $st) {
//print "<p>".  $st->source_text;           
            $st->source_id=2;
            $st->mention = preg_replace("/^SNA\:\s*(.+)$/", "$1", $st->source_text);
            $st->source_text = '';
//print " ----- ".  $st->mention ." ----- ".  $st->source_text ."</p>";           
            $st->save();
        }
 * 
 */
        $sources = SourceToponym::where('source_text', 'like', 'Karhe, 1940')
                    ->whereSourceId(2)->get();
        foreach ($sources as $st) {
            $ev=$st->toponym->events()->first();
print "<p>".$st->toponym->id.', '.$ev->date.', '.optional($ev->recorders()->first())->name;
//.optional($ev->settlement)->name.', '.', '.optional($ev->informant)->name
print "</p>";
        }
    }
}
