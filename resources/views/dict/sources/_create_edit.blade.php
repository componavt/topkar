        <div class='row'>
            <div class="col-sm-1 compact-field">
                @include('widgets.form.formitem._text', 
                    ['name' => $var_name.'['.$num.'][sequence_number]', 
                     'value' => $source->sequence_number ?? $num,
            ])
            </div>
            <div class="col-sm-5">
            @include('widgets.form.formitem._textarea', 
                    ['name' => $var_name.'['.$num.'][mention]', 
                     'special_symbol' => true,
                     'attributes' => ['rows' => 3],
                     'value' => $source->mention ?? null,
            ]) 
            </div>
            <div class="col-sm-6">
            @include('widgets.form.formitem._textarea', 
                    ['name' => $var_name.'['.$num.'][source]', 
                     'special_symbol' => true,
                     'attributes' => ['rows' => 3],
                     'value' => $source->source ?? null,
            ]) 
            </div>            
        </div>
