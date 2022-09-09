<div class='row'>
    <div class="col-sm-8">
        @include('widgets.form.formitem._select2',
                ['name' => 'districts['.$i.'][id]', 
                 'values' =>$district_values,
                 'value' => $district['id'] ?? '',
                 'is_multiple' => false,
                 'title' => trans('toponym.district'),
                 'class'=>'select-district-'.$i.' form-control'])
    </div>
    <div class="col-sm-2">          
        @include('widgets.form.formitem._text', 
                ['name' => 'districts['.$i.'][from]',
                 'title' => 'c',
                 'value' => $district['from'] ?? '',
                 'attributes' => ['size'=>4, 'placeholder'=>'гггг']])
    </div>
    <div class="col-sm-2">        
        @include('widgets.form.formitem._text', 
                ['name' => 'districts['.$i.'][to]',
                 'title' => 'по',
                 'value' => $district['to'] ?? '',
                 'attributes' => ['size'=>4, 'placeholder'=>'гггг']])
    </div>
</div>