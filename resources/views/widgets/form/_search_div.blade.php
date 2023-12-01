<?php 
if (!isset($submit_value) || !$submit_value) {
    $submit_value = 'view';
}

?>
    <div class="col-sm-4 search-button-b">       
        <span>{{ __('search.show_by') }}</span>
        <input placeholder="{{ __('messages.portion') }}" id="portion" name="portion" type="text" value="{{ $url_args['portion'] }}" size="3">
        <span>{{ __('messages.records') }}</span>
        @if (!empty($with_clear))
        <input type="reset" class="btn btn-grey btn-default" value="{{ __('messages.clear') }}">
        @endif
        @include('widgets.form.formitem._submit', ['title' => trans('messages.'.$submit_value)])
    </div>
