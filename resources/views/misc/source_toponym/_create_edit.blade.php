        <div class='row'>
            <div class="col-sm-1">
                @include('widgets.form.formitem._text', 
                    ['name' => $var_name.'['.$num.'][sequence_number]', 
                     'value' => $st->sequence_number ?? $num,
            ])
            </div>
            <div class="col-sm-3">
            @include('widgets.form.formitem._textarea', 
                    ['name' => $var_name.'['.$num.'][mention]', 
                     'special_symbol' => true,
                     'attributes' => ['rows' => 3],
                     'value' => $st->mention ?? null,
            ]) 
            </div>
            <div class="col-sm-4">
            @include('widgets.form.formitem._select', 
                    ['name' => $var_name.'['.$num.'][source_id]', 
                     'values' => $source_values,
                     'value' => $st->source_id ?? null
            ]) 
            </div>            
            <div class="col-sm-4">
            @include('widgets.form.formitem._textarea', 
                    ['name' => $var_name.'['.$num.'][source_text]', 
                     'special_symbol' => true,
                     'attributes' => ['rows' => 3],
                     'value' => $st->source_text ?? null,
            ]) 
            </div>
        </div>
