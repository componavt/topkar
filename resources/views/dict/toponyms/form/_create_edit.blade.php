@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row"><!-- First row -->
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name', 
                 'special_symbol' => true,
                 'full_special_list' => true,
                 'title'=>trans('toponym.official_name')])
{{--                 'help_func' => "callHelp('help-text-fields')",--}}

        <div class="form-group ">
            <label for="name">
                {{trans('toponym.topnames')}}
                <i onclick="addTopName()" class="call-add fa fa-plus fa-lg" title="{{trans('messages.insert_new_field')}}"></i>
            </label>
            @foreach($topnames as $topname)
                @include('dict.topnames._create_edit',
                    ['id_name'=>'topnames_'.$topname->id, 
                     'var_name'=>'topnames['.$topname->id.']',
                     'value' => $topname->name
                    ])
            @endforeach
            <input type='hidden' name='next-name-num' value='0'>
            <div id='new-topnames'></div>
        </div>        
        <!-- Region -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'region_id', 
                 'values' => $region_values,
                 'value' => optional($toponym)->regionValue(),
                 'title' => trans('toponym.region'), 
                 'is_multiple' => false,
                 'class'=>'select-region form-control'
                 ])

        <!-- District -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district_id', 
                 'values' => $district_values,
                 'value' => optional($toponym)->districtValue(),
                 'title' => trans('toponym.district'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addDistrict()",
                 'class'=>'select-district form-control'
        ]) 
        
        <!-- Settlement -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'settlement_id', 
                 'values' => $settlement_values,
                 'value' => optional($toponym)->settlementValue(),
                 'title' => trans('toponym.settlement'),
                 'call_add_onClick' => "addSettlement()",
                 'class'=>'select-settlement form-control'
        ]) 
                
        @include('widgets.form.formitem._text', 
                ['name' => 'caseform', 
                 'title'=>trans('toponym.caseform')])
    </div>
    <div class="col-sm-6"><!-- Second column -->
        <!-- Geotype -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'geotype_id', 
                 'values' => $geotype_values,
                 'value' => optional($toponym)->geotypeValue(),
                 'title' => trans('misc.geotype'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addGeotype()",
                 'class'=>'select-geotype form-control'
        ])
        
        <!-- Region 1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'region1926_id', 
                 'values' => $region_values,
                 'value' => optional($toponym)->region1926Value(),
                 'title' => trans('toponym.region1926'), 
                 'is_multiple' => false,
                 'class'=>'select-region1926 form-control'
                 ])
                 
        <!-- District1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district1926_id', 
                 'values' => $district1926_values,
                 'value' => optional($toponym)->district1926Value(),
                 'title' => trans('toponym.district1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addDistrict('1926')",
                 'class'=>'select-district1926 form-control'
        ]) 
        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'selsovet1926_id', 
                 'values' => $selsovet1926_values,
                 'value' => optional($toponym)->selsovet1926Value(),
                 'title' => trans('toponym.selsovet1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addSelsovet1926()",
                 'class'=>'select-selsovet1926 form-control'
        ]) 
        
        <!-- Settlement1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'settlement1926_id', 
                 'values' => $settlement1926_values,
                 'value' => optional($toponym)->settlement1926Value(),
                 'title' => trans('toponym.settlement1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addSettlement1926()",
                 'class'=>'select-settlement1926 form-control'
        ]) 
    </div><!-- eo Second column -->    
</div><!-- eo First row -->

<div class="row"><!-- Second row-->
    <div class="col-sm-6">
        <!-- Main information -->
        @include('widgets.form.formitem._textarea', 
                ['name' => 'main_info', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.main_info')])
        <!-- Popular interpretation -->
        @include('widgets.form.formitem._textarea', 
                ['name' => 'folk', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.folk')])
        <!-- Legend -->
        @include('widgets.form.formitem._textarea', 
                ['name' => 'legend', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.legend')])
        <!-- Sources -->                 
        <b>{{trans('toponym.sources')}}</b>
        <i onclick="addSource()" class="call-add fa fa-plus fa-lg" title="{{trans('messages.insert_new_field')}}"></i>
        <div class='row'>
            <div class="col-sm-1"></div>
            <div class="col-sm-5"><b>{{trans('toponym.mention')}}</b></div>
            <div class="col-sm-6"><b>{{trans('toponym.source')}}</b></div>            
        </div>
        @if ($action == 'edit') 
            @foreach ($toponym->sources as $source)
                @include('dict.sources._create_edit', ['num'=>$source->id, 'var_name'=>'sources'])
            @endforeach
        @endif
        <input type='hidden' id='next-source-num' value='{{1 + (isset($source) ? $source->sequence_number : 0) }}'>
        <div id='new-sources'></div>
    </div>
    <div class="col-sm-6">
        <!-- Etymology nation -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'etymology_nation_id', 
                 'values' => $etymology_nation_values,
                 'value' => optional($toponym)->etymologyNationValue(),
                 'title' => trans('misc.etymology_nation'),
                 'is_multiple' => false,
                 'class'=>'select-etymology-nation form-control'
        ])
        @include('widgets.form.formitem._select2', 
                ['name' => 'ethnos_territory_id', 
                 'values' => $ethnos_territory_values,
                 'value' => optional($toponym)->ethnosTerritoryValue(),
                 'title' => trans('misc.ethnos_territory'),
                 'is_multiple' => false,
                 'class'=>'select-ethnos-territory form-control'
        ])
        @include('widgets.form.formitem._textarea', 
                ['name' => 'etymology', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.etymology')])
        <p><b>{{trans('misc.struct')}}</b></p>
        @for ($i=0; $i < sizeof($structs); $i++)
        <div class='row'>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select', 
                    ['name' => 'structhiers['.$i.']', 
                     'values' => $structhier_values,
                     'value' => $structhiers[$i],
                     'grouped' => true,
                     'attributes'=>['placeholder'=>trans('misc.structhier')]
            ]) 
            </div>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select2', 
                    ['name' => 'structs['.$i.']', 
                     'values' => $struct_values,
                     'value' => $structs[$i],
                     'is_multiple' => false,
                     'class'=>'select-struct'.$i.' form-control'
            ]) 
            </div>            
        </div>
        @endfor
    </div>
</div><!-- eo Second row -->

<!-- Events -->                
<?php $count=1;?>
<div class='row'>
    <div class="col-sm-4"><b>{{trans('misc.record_place')}}</b></div>
    <div class="col-sm-2"><b>{{mb_ucfirst(trans('messages.year'))}}</b></div>
    <div class="col-sm-3"><b>{{trans('navigation.informants')}}</b></div>            
    <div class="col-sm-3"><b>{{trans('navigation.recorders')}}</b></div>            
</div>
@if ($action == 'edit') 
    @foreach ($toponym->events as $event)
        @include('misc.events._create_edit', [
            'num' => $count++,
            'var_name'=>"events[".$event->id."]", 
            'settlements_value' => $event->settlementsValue(),
            'informants_value' => $event->informantsValue(),
            'recorders_value' => $event->recordersValue(),
        ])
    @endforeach
@endif

@include('misc.events._create_edit', [
            'num' => $count,
            'var_name'=>"new_event",
            'settlements_value' => [],
            'informants_value' => [],
            'recorders_value' => [],
            'event' => null,
])

@include('widgets.form.formitem._submit', ['title' => $submit_title])
