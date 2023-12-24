<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Pivovarov aka AngryDeer http://studioweb.pro
 * Date: 25.01.16
 * Time: 4:41
 * Updated: 24.08.2016 by Nataly Krizhanovsky
 */?>
<?php 
if(!isset($attributes['class'])) 
    $attributes['class'] = 'form-control';

$id_name = preg_replace("/[\.\]\[]/","_",$name);
$attributes['id'] = $id_name;
?>
<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}{{ !empty($special_symbol) ? ' with-special' : null }}">
    @if(!empty($title))
	<label for="{{$name}}">{{ $title }}</label>
        <span class='imp'>{!!isset($help_text) ? $help_text : ''!!}</span>
    @endif
    {!! Form::textarea($name, $value ?? null, $attributes) !!}
    @if (!empty($special_symbol))
        @include('widgets.special_symbols',['id_name'=>$id_name])
    @endif
    <p class="help-block">
        {!! $errors->first($name) !!}
    </p>
</div>