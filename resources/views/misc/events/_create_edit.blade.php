        <div class='row'>
            <div class="col-sm-2">
            @include('widgets.form.formitem._select2', 
                    ['name' => $var_name.'[settlements]', 
                     'values' => $settlement_values,
                     'value' => $settlements_value,
                     'class'=>'select-event-place'.$num.' form-control'
            ]) 
            </div>
            <div class="col-sm-3">
            @include('widgets.form.formitem._select2', 
                    ['name' => $var_name.'[settlements1926]', 
                     'values' => $settlement1926_values,
                     'value' => $settlements1926_value,
                     'class'=>'select-event-place1926_'.$num.' form-control'
            ]) 
            </div>
            <div class="col-sm-2">
            @include('widgets.form.formitem._text', 
                    ['name' => $var_name.'[date]', 
                     'value' => $event->date ?? null,
            ]) 
            </div>
            <div class="col-sm-2">
            @include('widgets.form.formitem._select2', 
                    ['name' => $var_name.'[informants]', 
                     'values' => $informant_values,
                     'value' => $informants_value,
                     'class'=>'select-informant'.$num.' form-control'
            ]) 
            </div>
            <div class="col-sm-3">
            @include('widgets.form.formitem._select2', 
                    ['name' => $var_name.'[recorders]', 
                     'values' => $recorder_values,
                     'value' => $recorders_value,
                     'class'=>'select-recorder'.$num.' form-control'
            ]) 
            </div>
        </div>
