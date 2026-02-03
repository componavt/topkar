@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>
<div class='row'>
    <div class="col-sm-6">
@include('widgets.form.formitem._textarea', 
        ['name' => 'name_ru', 
         'attributes' => ['rows' => 3],
         'title'=>trans('toponym.name').' на оригинальном языке'])
@include('widgets.form.formitem._text', 
        ['name' => 'short_ru', 
         'title'=>trans('toponym.short_name')])
@include('widgets.form.formitem._text', 
        ['name' => 'year', 
         'title'=>trans('messages.year')])
    </div>
    <div class="col-sm-6">
@include('widgets.form.formitem._textarea', 
        ['name' => 'name_en', 
         'attributes' => ['rows' => 3],
         'title'=>trans('toponym.name').' '.trans('messages.in_english')])
@include('widgets.form.formitem._text', 
        ['name' => 'short_en', 
         'title'=>trans('toponym.short_name').' '.trans('messages.in_english')])
    </div>
</div>