<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Misc\Source;
use App\Models\Misc\SourceToponym;

class SourceToponymController extends Controller
{
    public function index() {
        $sources = SourceToponym::whereNull('source_id')
                         ->groupBy('source_id', 'source_text')
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
 
//select source_text from source_toponym where source_text like '[НАРК,%].';        
        $sources = SourceToponym::where('source_text', 'like', '[НАРК,%].')
                //->take(1)
                ->get();
        foreach ($sources as $st) {
print "<p><a href=\"".route('toponyms.show',$st->toponym)."\">".$st->toponym->id.'</a>, '.  $st->source_text;           
            $st->source_id=5;
            $st->source_text = preg_replace("/^\[НАРК,\s*(.+)\]\.$/", "$1", $st->source_text);
print " ----- ".  $st->source_text ."</p>";           
            $st->save();
        }

        $sources = SourceToponym::where('source_text', 'like', '[SNA, Karhe, 1940]')
                    //->whereSourceId(2)
                    ->get();
        foreach ($sources as $st) {
            $ev=$st->toponym->events()->first();
print "<p><a href=\"".route('toponyms.show',$st->toponym)."\">".$st->toponym->id.'</a>, '.$ev->date.', '.optional($ev->recorders()->first())->name;
//.optional($ev->settlement)->name.', '.', '.optional($ev->informant)->name
print "</p>";
        }
 */
/*
        $source_id=2;
//        $sources = SourceToponym::where('source_text', 'like', '[SNA, Hotari, 1954]')
//        $sources = SourceToponym::where('source_text', 'like', '[SNA, Kiviniemi, 1963]')
//        $sources = SourceToponym::where('source_text', 'like', '[SNA, Iltola, 1964]')
//        $sources = SourceToponym::where('source_text', 'like', '[SNA, Hotari, 1953]')
//        $sources = SourceToponym::where('source_text', 'like', '[SNA, Bjarland, 1974]')
        $sources = SourceToponym::where('source_text', 'like', '[SNA, Muronen, 1957]')
                    //->whereSourceId(2)
                    ->get();
        foreach ($sources as $st) {
            $ev=$st->toponym->events()->first();
print "<p><a href=\"".route('toponyms.show',$st->toponym)."\">".$st->toponym->id.'</a>, '.$ev->date.', '.optional($ev->recorders()->first())->name;
//.optional($ev->settlement)->name.', '.', '.optional($ev->informant)->name
            $st->source_id=$source_id;
            $st->source_text='';
print " ----- ". $st->source->short. " ----- ".  $st->source_text;           
print "</p>";
//            $st->save();
        }
*/        
        
        $template = 'КАССР АТД 1975';
        $source_id= 42;
        $sources = SourceToponym::where('source_text', 'like', '%'.$template.'%')

//        $sources = SourceToponym::where('source_text', 'like', $template)             // 1 t
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.']')     // 7 [t]
//        $sources = SourceToponym::where('source_text', 'like', $template.', %')       // 2 t, s
//        $sources = SourceToponym::where('source_text', 'like', $template.'. %.')      // 3
//        $sources = SourceToponym::where('source_text', 'like', $template.'. %')       // 4
//        $sources = SourceToponym::where('source_text', 'like', $template.' %.')       // 5 t s.
//        $sources = SourceToponym::where('source_text', 'like', $template.' %')        // 6 t s
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.' %]')   // 8 [t s]  
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.']%.')   // 15 [t] s.  
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.']%')   // 14 [t] s  
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.' %]%')  // 12 [t s] s  
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.'%]%')   // 13 [ts] s  
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.', %]')  // 9 [t, s]
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.'. %]')  // 16 [t. s]
//        $sources = SourceToponym::where('source_text', 'like', '['.$template.', %].') // 10 [t, s].

//        $sources = SourceToponym::where('source_text', 'like', '%: '.$template.', %') // 11
                ->get();
        foreach ($sources as $st) {
print "<p><a href=\"".route('toponyms.edit',$st->toponym)."\">".$st->toponym->id.'</a>, '.  $st->source_text;           

            $st->source_id=$source_id;

//            $st->source_text = '';                                                                        // 1 t   // 7 [t]
//            $st->source_text = preg_replace("/^".$template.",\s*(.*)$/m", "$1", $st->source_text);        // 2 t, s
//            $st->source_text = preg_replace("/^".$template."\.\s*(.*)\.$/m", "$1", $st->source_text);     // 3
//            $st->source_text = preg_replace("/^".$template."\.\s*(.*)$/m", "$1", $st->source_text);       // 4
//            $st->source_text = preg_replace("/^".$template."\s*(.*)\.$/m", "$1", $st->source_text);       // 5 t s.
//            $st->source_text = preg_replace("/^".$template."\s*(.*)$/m", "$1", $st->source_text);         // 6 t s
//            $st->source_text = preg_replace("/^\[".$template."\s*(.*)\]$/m", "$1", $st->source_text);     // 8 [t s]
//            $st->source_text = preg_replace("/^\[".$template."\]\s*(.*)\.$/m", "$1", $st->source_text);     // 15 [t] s.
//            $st->source_text = preg_replace("/^\[".$template."\]\s*(.*)$/m", "$1", $st->source_text);     // 14 [t] s
//            $st->source_text = preg_replace("/^\[".$template."\s*(.*)\]\s*(.*)$/m", "$1. $2", $st->source_text);     // 12 [t s] s   // 13 [ts] s  
//            $st->source_text = preg_replace("/^\[".$template.",\s*(.*)\]$/m", "$1", $st->source_text);    // 9 [t, s]
//            $st->source_text = preg_replace("/^\[".$template."\.\s*(.*)\]$/m", "$1", $st->source_text);    // 16 [t. s]
//            $st->source_text = preg_replace("/^\[".$template.",\s*(.*)\]\.$/m", "$1", $st->source_text);  // 10 [t, s].

print " ----- ". $st->source->short. " ----- ".  $st->source_text;           
/*
            if (preg_match("/^(.+):\s*".$template."\,\s*(.*)$/", $st->source_text, $regs)) {                // 11
                $st->mention = $regs[1];
                $st->source_text = $regs[2];
            }
print " ----- ". $st->source->short. " ----- ".  $st->source_text. " ----- ".  $st->mention;


/*        $sources = SourceToponym::whereSourceId($source_id)
//                    ->where('source_text', 'like', '%.')
                ->get();
        foreach ($sources as $st) {
print "<p><a href=\"".route('toponyms.edit',$st->toponym)."\">".$st->toponym->id.'</a>, '.  $st->source_text;           

            $st->source_text = preg_replace("/^(.*)\.$/m", "$1", $st->source_text);
print " ----- ". $st->source_text;
print "</p>";           
*/
//            $st->save();
        }
    }
}
