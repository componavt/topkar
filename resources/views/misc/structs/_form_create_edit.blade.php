<input id='locale' type='hidden' name='locale' value='{{app()->getLocale()}}'>
@include('widgets.form.formitem._select', 
        ['name' => 'structhier_id', 
         'values' => $structhier_values,
         'grouped' => true,
         'attributes'=>['placeholder'=>trans('misc.structhier')]
]) 
@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('toponym.name')])
</div>