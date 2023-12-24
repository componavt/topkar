<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Pivovarov aka AngryDeer http://studioweb.pro
 * Date: 25.01.16
 * Time: 4:41
 * Updated: 24.08.2016 by Nataly Krizhanovsky
 */?>
<?php 
if (!isset($attributes)) {
    $attributes = [];
}

if(isset($attributes['size'])) {
    $attributes['class'] = 'form-control-sized';
    
} elseif (!isset($attributes['class'])) {
    $attributes['class'] = 'form-control';
}

if (isset($class)) {
    $attributes['class'] .= ' '.$class;
}

$id_name = preg_replace("/[\.\]\[]/","_",$name);
$attributes['id'] = $id_name;

?>
<div class="form-group{!! $errors->has($name) ? ' has-error' : null !!}{{ !empty($special_symbol) ? ' with-special' : null }}">
    @if(!empty($title))
	<label for="{{$name}}">{{ $title }}&nbsp;</label>
    @endif
    {!! Form::text($name, $value ?? null, $attributes) !!}

    @if (!empty($field_comments))
    <span class='field_comments'>{{$field_comments}}</span>
    @endif
    
    @if (!empty($special_symbol)) 
        @include('widgets.special_symbols',['id_name'=>$id_name])
    @endif
    
    @if (!empty($help_func)) 
    <i class='help-icon far fa-question-circle fa-lg' onClick='{{$help_func}}'></i>
    @endif
    
    {{ $tail ?? '' }}           
    @if ($errors->first($name))
    <p class="help-block">{!! $errors->first($name) !!}</p>
    @endif
</div>