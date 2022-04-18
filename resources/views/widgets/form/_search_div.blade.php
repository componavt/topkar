<?php 
if (!isset($submit_value) || !$submit_value) {
    $submit_value = 'view';
}

?>
    <div class="col-sm-3 search-button-b">       
        <span>
        {{trans('search.show_by')}}
        </span>
        @include('widgets.form.formitem._text', 
                ['name' => 'portion', 
                'value' => $url_args['portion'], 
                'attributes'=>['placeholder' => trans('messages.portion') ]]) 
        <span>
                {{ trans('messages.records') }}
        </span>
        @include('widgets.form.formitem._submit', ['title' => trans('messages.'.$submit_value)])
    </div>
