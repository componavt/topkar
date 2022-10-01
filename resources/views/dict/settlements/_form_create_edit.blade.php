<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>

@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'geotype_id', 
                 'values' =>$type_values,
                 'title' => trans('misc.type')]) 
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('toponym.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_krl', 
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_karelian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_en', 
                 'special_symbol' => true,
                 'title'=>trans('toponym.name').' '.trans('messages.in_english')])
                                  
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'region_id', 
                 'values' =>$region_values,
                 'title' => trans('toponym.region')]) 

        <?php $i=0;?>
        @foreach ($district_value ?? [] as $district)
            @include('dict.settlements._form_district_group', ['district'=>$district])
            <?php $i++;?>
        @endforeach
        @include('dict.settlements._form_district_group', 
            ['district'=> ['id'=>isset($action) && $action=='creation' && isset($url_args['search_districts'][0]) 
                                ? $url_args['search_districts'][0] : null]])        
    </div>
</div> 

