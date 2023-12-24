        <div class="row">        
            <div class="col-sm-6">
                <p class="form-group with-special">
                    <input id="{{$id_name}}" class="form-control" name="{{$var_name}}[n]" type="text" value="{{$name ?? null}}">
                    @include('widgets.special_symbols',['full_special_list' => true])
                </p>
            </div>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select', 
                    ['name' => $var_name.'[l]', 
                     'value' => $lang_id ?? null,
                     'values' => $lang_values
                     
            ]) 
            </div>
        </div>
