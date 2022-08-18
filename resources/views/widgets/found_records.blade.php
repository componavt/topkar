<p>
{!!trans_choice('search.found_count', 
            $n_records%10==0 ? $n_records : ($n_records%100>20 ? $n_records%10  : $n_records%100), 
            ['count'=>number_format($n_records, 0, ',', ' ')])!!}
</p>
