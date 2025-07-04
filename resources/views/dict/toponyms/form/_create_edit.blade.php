@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        <div class="row">        
            <div class="col-sm-6">
            @include('widgets.form.formitem._text', 
                    ['name' => 'name', 
                     'special_symbol' => true,
                     'full_special_list' => true,
                     'title'=>trans('toponym.official_name')])
{{--                 'help_func' => "callHelp('help-text-fields')",--}}
            </div>
            <div class="col-sm-6">
            @include('widgets.form.formitem._select', 
                    ['name' => 'lang_id', 
                     'values' => $lang_values,
                     'title' => trans('toponym.lang')
            ]) 
            </div>
        </div>
        @if(!empty($toponym) && $toponym->accent)
            Код ударения: {{$toponym->accent}} 
            (<a href="https://docs.google.com/spreadsheets/d/1jCoNpgLU0w-rUcnPogznJqacoU4EgAZE9bhuG14gGDU/edit?usp=sharing">документация к старым ударениям</a>)<br><br>
        @endif
        
        <!-- Other names -->
        <div class="form-group ">
            <label for="name">
                {{trans('toponym.topnames')}}
                <i id='add-top-name' onclick="addTopName('{{app()->getLocale()}}')" class="call-add fa fa-plus fa-lg" data-count=0 title="{{trans('messages.insert_new_field')}}"></i>
            </label>
            @foreach($topnames as $topname)
                @include('dict.topnames._create_edit',
                    ['id_name'=>'topnames_'.$topname->id, 
                     'var_name'=>'topnames['.$topname->id.']',
                     'name' => $topname->name,
                     'lang_id'=> $topname->lang_id
                    ])
            @endforeach
            <div id='new-topnames'></div>
        </div>  
        
        <!-- Wrong names -->
        <div class="form-group ">
            <label for="name">
                {{trans('toponym.wrongnames')}}
                <i id='add-wrong-name' onclick="addWrongName('{{app()->getLocale()}}')" class="call-add fa fa-plus fa-lg" data-count=0 title="{{trans('messages.insert_new_field')}}"></i>
            </label>
            @foreach($wrongnames as $wrongname)
                @include('dict.wrongnames._create_edit',
                    ['id_name'=>'wrongnames_'.$wrongname->id, 
                     'var_name'=>'wrongnames['.$wrongname->id.']',
                     'name' => $wrongname->name,
                     'lang_id'=> $wrongname->lang_id
                    ])
            @endforeach
            <div id='new-wrongnames'></div>
        </div>        
        <!-- Region -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'region_id', 
                 'values' => $region_values,
                 'value' => $action=='create' && isset($region_value) ? $region_value : optional($toponym)->regionValue(),
                 'title' => trans('toponym.region'), 
                 'is_multiple' => false,
                 'class'=>'select-region form-control'
                 ])

        <!-- District -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district_id', 
                 'values' => $district_values,
                 'value' => $action=='create' && isset($district_value) ? $district_value : optional($toponym)->districtValue(),
                 'title' => trans('toponym.district'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addDistrict()",
                 'class'=>'select-district form-control'
        ]) 
        
        <!-- Settlement -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'settlement_id', 
                 'values' => $settlement_values,
                 'value' => $action=='create' && isset($settlement_value) ? $settlement_value : optional($toponym)->settlementValue(),
                 'title' => trans('toponym.settlement'),
                 'call_add_onClick' => "addSettlement()",
                 'class'=>'select-settlement form-control'
        ]) 
                
        <!-- Locative form -->
        @include('widgets.form.formitem._text', 
                ['name' => 'caseform', 
                 'title'=>trans('toponym.caseform')])
                 
        <!-- Main information -->
        @include('widgets.form.formitem._textarea', 
                ['name' => 'main_info', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.main_info')])
            <div class="row">
                <div class="col-sm-8">
                <!-- Popular interpretation, Legend -->
                @include('widgets.form.formitem._textarea', 
                        ['name' => 'legend', 
                         'special_symbol' => true,
                         'attributes' => ['rows' => 3],
                         'title'=>trans('toponym.legend')])
                </div>
                <div class="col-sm-4">
                    @include('widgets.form.formitem._text', 
                            ['name' => 'text_ids', 
                             'value' => optional($toponym)->textIds(),
                             'title'=>trans('toponym.vepkar_text_id')])
                </div>
            </div>    
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
        
        <div class='row' style='margin-bottom: 10px; font-size: 16px'>
            <div class="col-sm-6">
                <a class="clickable" onClick="callMap()">Указать координаты на карте</a>
            </div>
        @if ($toponym && !$toponym->hasCoords() && $toponym->objOnMap()) 
            <div class="col-sm-6">
                <a id="settlement-coords" class="clickable" data-lat="{{ $toponym->objOnMap()->latitude }}" data-lon="{{ $toponym->objOnMap()->longitude }}">скопировать координаты у поселения {{ $toponym->objOnMap()->name }}</a>
            </div>
        @endif
        </div>                 
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
                 'value' => $action=='create' && isset($region1926_value) ? $region1926_value : optional($toponym)->region1926Value(),
                 'title' => trans('toponym.region1926'), 
                 'is_multiple' => false,
                 'class'=>'select-region1926 form-control'
                 ])
                 
        <!-- District1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'district1926_id', 
                 'values' => $district1926_values,
                 'value' => $action=='create' && isset($district1926_value) ? $district1926_value : optional($toponym)->district1926Value(),
                 'title' => trans('toponym.district1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addDistrict('1926')",
                 'class'=>'select-district1926 form-control'
        ]) 
        
        <!-- Selsovet1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'selsovet1926_id', 
                 'values' => $selsovet1926_values,
                 'value' => $action=='create' && isset($selsovet1926_value) ? $selsovet1926_value : optional($toponym)->selsovet1926Value(),
                 'title' => trans('toponym.selsovet1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addSelsovet1926()",
                 'class'=>'select-selsovet1926 form-control'
        ]) 
        
        <!-- Settlement1926 -->
        @include('widgets.form.formitem._select2', 
                ['name' => 'settlement1926_id', 
                 'values' => $settlement1926_values,
                 'value' => $action=='create' && isset($settlement1926_value) ? $settlement1926_value : optional($toponym)->settlement1926Value(),
                 'title' => trans('toponym.settlement1926'),
                 'is_multiple' => false,
                 'call_add_onClick' => "addSettlement1926()",
                 'class'=>'select-settlement1926 form-control'
        ]) 
        
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
                 'value' => $action=='create' && !empty($ethnos_territory_value) ? $ethnos_territory_value : optional($toponym)->ethnosTerritoryValue(),
                 'title' => trans('misc.ethnos_territory'),
                 'is_multiple' => false,
                 'class'=>'select-ethnos-territory form-control'
        ])
        @include('widgets.form.formitem._textarea', 
                ['name' => 'etymology', 
                 'special_symbol' => true,
                 'attributes' => ['rows' => 3],
                 'title'=>trans('toponym.etymology')])
                 
        <p><b>{{ trans('misc.struct') }}</b></p>
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
</div><!-- eo row -->

<!-- SourceToponyms -->    
<div style="background-color: #eceeed; margin-bottom: 20px">
    <b>{{trans('toponym.sources')}}</b>
    <i onclick="addSourceToponym('{{app()->getLocale()}}')" class="call-add fa fa-plus fa-lg" title="{{trans('messages.insert_new_field')}}"></i>
    <div class='row'>
        <div class="col-sm-1"></div>
        <div class="col-sm-3"><b>{{trans('toponym.mention')}}</b></div>
        <div class="col-sm-4"><b>{{trans('toponym.source')}}</b></div>            
    </div>
    @if ($action == 'edit') 
        @foreach ($toponym->sourceToponyms as $st)
            @include('misc.source_toponym._create_edit', ['num'=>$st->id, 'var_name'=>'source_toponym'])
        @endforeach
    @endif
    <input type='hidden' id='next-source_toponym-num' value='{{1 + (isset($st) ? $st->sequence_number : 0) }}'>
    <div id='new-source_toponym'></div>
</div>  

<!-- Events -->                
<?php $count=1;?>
    <h3>{{trans('misc.record_places')}}</h3>
<div style="background-color: #eceeed">
<div class='row'>
    <div class="col-sm-2"><b>{{trans('toponym.place_now')}}</b></div>
    <div class="col-sm-3"><b>{{trans('toponym.place_early')}}</b></div>
    <div class="col-sm-2"><b>{{mb_ucfirst(trans('messages.year'))}}</b></div>
    <div class="col-sm-2"><b>{{trans('navigation.informants')}}</b></div>            
    <div class="col-sm-3"><b>{{trans('navigation.recorders')}}</b></div>            
</div>
@if ($action == 'edit') 
    @foreach ($toponym->events as $event)
        @include('misc.events._create_edit', [
            'num' => $count++,
            'var_name'=>"events[".$event->id."]", 
            'settlements_value' => $event->settlementsValue(),
            'settlements1926_value' => $event->settlements1926Value(),
            'informants_value' => $event->informantsValue(),
            'recorders_value' => $event->recordersValue(),
        ])
    @endforeach
@elseif(!empty($event_value))
    @foreach ($event_value as $event)
        @include('misc.events._create_edit', [
            'num' => $count,
            'var_name'=>"new_events[".$count++."]", 
            'settlements_value' => $event->settlementsValue(),
            'settlements1926_value' => $event->settlements1926Value(),
            'informants_value' => $event->informantsValue(),
            'recorders_value' => $event->recordersValue(),
        ])
    @endforeach
@endif

@include('misc.events._create_edit', [
            'num' => $count,
            'var_name'=>"new_events[".$count."]",
            'settlements_value' => [],
            'settlements1926_value' => [],
            'informants_value' => [],
            'recorders_value' => [],
            'event' => null,
])
</div>
@include('widgets.form.formitem._submit', ['title' => $submit_title])
