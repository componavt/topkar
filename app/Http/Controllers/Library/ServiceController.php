<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

use DB;

//use App\Models\Dict\Source;
use App\Models\Dict\Settlement;
//use App\Models\Dict\Topname;
use App\Models\Dict\Toponym;

use App\Models\Misc\Event;
use App\Models\Misc\Informant;
use App\Models\Misc\Recorder;

class ServiceController extends Controller
{
    // service/tmp_fill_events
    public function tmp_fill_events() {
/*        $records = DB::table('RECORDS')
//                     ->where('recorder', 'like', '%КГПИ%')
                     ->where('informant', 'like', ' %')
                //->take(1)
                ->get();
        foreach ($records as $record) {
            DB::statement("update RECORDS set informant='".trim($record->informant)."' where id=".$record->id);
        }
*/
        // delete from RECORDS where recorder like '%КГПИ%';
        // delete from RECORDS where informant like 'ТК%';
/*        $records = DB::table('RECORDS')
//                     ->where('recorder', 'like', '%КГПИ%')
                     ->where('informant', 'like', 'ТК%')
                //->take(1)
                ->get();
        print "<table border=1>";
        foreach ($records as $record) {
            $toponym = Toponym::find($record->toponym_id);
            print "<tr><td>".$record->id."</td><td>".$record->recorder."</td><td><td>"
                    .$record->informant."</td><td>".$record->record_year."</td><td>"
                    .$record->record_place."</td><td><a href=\"/ru/dict/toponyms/".$toponym->id."/edit\">&nbsp;".
                    join('<br>',$toponym->sources()->pluck('source')->toArray())."</a></td></tr>";
            if (!$toponym->sources()->count()) {
                Source::storeData($toponym->id, 
                        ['mention'=>'', 'sequence_number' => 1,
                        'source' => $record->informant. ($record->record_year ? ', '.$record->record_year : '')]);
//                        'source' => $record->recorder. ($record->record_year ? ', '.$record->record_year : '')]);
 //               DB::statement('delete from RECORDS where id='.$record->id);
            }
        }
        print "</table>";*/
/*        
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
        $records = DB::table('RECORDS')->orderBy('id')
                    ->where('id', '>', 4)
//                     ->take(1)
                    ->get();
print "<PRE>";        
        foreach ($records as $record) {
var_dump ($record);            
//dd($record, $toponym->id, $informants, $recorders, $places);            
            $toponym = Toponym::find($record->toponym_id);
            $informants = preg_split("/\s*,\s* /", trim($record->informant));
            $settlements = preg_split("/\s*,\s* /", trim($record->record_place));
            $recorders = preg_split("/\s*,\s* /", trim($record->recorder));
            
            $event = Event::create(['toponym_id'=>$toponym->id, 'date'=>$record->record_year]);
                        
            foreach ($settlements as $settlement) {
                if (!trim($settlement)) {
                    continue;
                }
                $settlement = Settlement::firstOrCreate(['name_ru'=>trim($settlement)]);
                $settlement->events()->attach($event->id);
                if ($toponym->district_id) {
                    $settlement->districts()->syncWithoutDetaching($toponym->district_id);
                }
print "<p>settlement: ".$settlement->name_ru.", ".($toponym->district_id ? 
            $settlement->districts()->wherePivot('district_id', $toponym->district_id)->first()->name_ru : 'NULL').'</p>';                
            }
            
            foreach ($informants as $informant_name) {
                $informant_name = trim($informant_name);
                if (!$informant_name) {
                    continue;
                }
                $birth_date = NULL;
                
                if (preg_match("/^([^\d\s]+)\s*(\d+)$/", $informant_name, $regs)) {
                    $informant_name = $regs[1];
                    $birth_date = $regs[2] ? $regs[2] : NULL;
                }

                $informant = Informant::firstOrCreate(['name_ru'=>$informant_name, 'birth_date'=>$birth_date]);
                $informant->events()->attach($event->id);
print "<p>informant: ".$informant->name_ru.", ".$informant->birth_date."</p>";                
            }
            
            foreach ($recorders as $recorder) {
                if (!trim($recorder)) {
                    continue;
                }
                $recorder = Recorder::firstOrCreate(['name_ru'=>trim($recorder)]);
                $recorder->events()->attach($event->id);
print "<p>recorder: ".$recorder->name_ru."</p>";                
            }
        }
print 'done.';  */     
    }
    
    // service/tmp_fill_settlements
    public function tmp_fill_settlements() {
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
         $toponyms = Toponym::whereNotNull('SETTLEMENT')
                ->get();
        foreach ($toponyms as $toponym) {
            foreach (preg_split("/\s*,\s*/", trim($toponym->SETTLEMENT)) as $settlement_name) {
                if (!$settlement_name) {
                    continue;
                }

                $settlements = Settlement::where('name_ru', 'like', $settlement_name);

                if ($settlements->count()>1) {
                    dd($settlements->count()." н.п. ".$settlement_name);
                } elseif ($settlements->count()==1 && $toponym->district_id) {
                    $settlement = $settlements->first();
                    if (!$settlement->districts()->wherePivot('district_id', $toponym->district_id)->count()) {
                        $settlement->districts()->attach($toponym->district_id);
                    }
                } else {
                    $settlement = Settlement::create(['name_ru'=>$settlement_name]);
                    $settlement->districts()->attach($toponym->district_id);
                }

                $toponym->settlements()->sync([$settlement->id]);
            }
        }
print 'done.';       
    }
    
/*    // service/tmp_fill_name_for_search
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
            foreach(preg_split("/\s*,\s* /", $toponym->VARIANTS) as $name) {
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
    
    
    // service/tmp_fill_sources
    public function tmp_fill_sources() {
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
        $toponyms = Toponym::whereNotNull('source')  
//                ->whereId(17)
                ->whereNotIn('id', [7516, 7536])
                ->where('id', '>', 7536)
//                ->take(10)
                ->orderBy('id')->get();
//dd($toponyms);  
        foreach ($toponyms as $toponym) {
            $last_source = Source::whereToponymId($toponym->id)->orderBy('sequence_number', 'desc')->first();
            $sequence_number = 1 + ($last_source ? $last_source->sequence_number : 0);
            
            foreach(preg_split("/\r\n/", $toponym->source) as $source) {
//                if (preg_match("/\(карта\)$/", trim($source))) {
//                    $is_map = 1;
//                } else {
//                    $is_map = 0;
//                }
                $source = trim($source);
                if (!$source) {
                    continue;
                }
                
                if (preg_match("/^(.*)(\[.+)$/", $source, $regs)) {
                    $mention = trim($regs[1]);
                    $source = trim($regs[2]);
                } else {
                    $mention = '';
                }
                    
                if (!Source::whereToponymId($toponym->id)
                          ->where('source', 'like', $source)
                          ->count()) {
//print "<p>".trim($source).'<br>'.$toponym->id.'|'.trim($regs[1]).'|'.$regs[2].'|'.$is_map.'</p>';                
                    Source::create([
                        'toponym_id' => $toponym->id,
                        'mention' => $mention, 
                        'source' => $source,
                        'sequence_number' => $sequence_number++,
//                        'is_map'=>$is_map
                    ]);
                }
            }
        }
print 'done.';       
    }
*/    
    
}
