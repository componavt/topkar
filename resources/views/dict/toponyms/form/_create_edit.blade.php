@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'toponym', 
                 'title'=>trans('dict.toponym')])
    </div>
</div>
@include('widgets.form.formitem._submit', ['title' => $submit_title])
