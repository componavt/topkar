<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form.formitem._select', 
        ['name' => 'region1926_id', 
         'values' =>$region_values,
         'title' => trans('toponym.region1926')]) 

@include('widgets.form.formitem._select2', 
        ['name' => 'district1926_id', 
         'values' => $district1926_values,
         'title' => trans('toponym.district1926'),
         'is_multiple' => false,
         'class'=>'select-district1926 form-control'
]) 
         
@include('widgets.form.formitem._select2', 
        ['name' => 'selsovet_id', 
         'values' => $selsovet1926_values,
         'value' => optional($settlement)->selsovet1926Value(),
         'title' => trans('toponym.selsovet1926'),
         'is_multiple' => false,
         'class'=>'select-selsovet1926 form-control'
]) 
         
@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('toponym.name').' '.trans('messages.in_russian')])

@include('widgets.form.formitem._text', 
        ['name' => 'name_en', 
         'title'=>trans('toponym.name').' '.trans('messages.in_english')])

@include('widgets.form.formitem._text', 
        ['name' => 'name_krl', 
         'title'=>trans('toponym.name').' '.trans('messages.in_karelian')])

        @if (!empty($with_coords))                        
        <div class="row"><!-- Row with coordinates and Wikidata ID-->
            <div class="col-sm-4">
                @include('widgets.form.formitem._text', 
                        ['name' => 'latitude', 
                         'title'=>trans('toponym.latitude')])
            </div>
            <div class="col-sm-4">
                @include('widgets.form.formitem._text', 
                        ['name' => 'longitude', 
                         'title'=>trans('toponym.longitude')])
            </div>
            <div class="col-sm-4"><!-- Wikidata ID without 'Q' -->
                @include('widgets.form.formitem._text', 
                        ['name' => 'wd', 
                         'title'=>trans('toponym.wd')])
            </div>
        </div>
        <p><a onClick="callMap()">Указать координаты на карте</a></p>
        @endif                        
