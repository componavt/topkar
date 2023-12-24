<div class="output-fields">    
    <div class='row'>
        <div class='col-sm-4'>
            @include('widgets.form.formitem._select', 
                    ['name' => 'sort_by', 
                     'values' => $sort_values,
                     'value' => $url_args['sort_by'],
                     ]) 
        </div>
        <div class='col-sm-8 output-fields-b'>
            <div class='output-fields-e'>
                <label><input name="in_desc" type="checkbox" hidden value="1"{{ $url_args['in_desc']==1 ? ' checked' : '' }}><span></span></label>
                <span>{!! trans('messages.in_desc') !!}</span>
            </div>
            <div class='output-fields-e'>
                <input class='form-control' id="portion" name="portion" type="text" value="{{ $url_args['portion'] }}">
                <span id='for-portion'>{!! __('messages.entries_per_page') !!}</span>
            </div>
            <input type="reset" class="btn btn-grey btn-default" value="{{ __('messages.clear') }}">
            <input type="submit" class="btn btn-primary btn-default" value="{{ __('messages.view') }}">
        </div>
    </div>
</div>    
