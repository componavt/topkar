@extends('layouts.master')

@section('header', trans('page.site_title'))

@section('main')   
    {!!trans('page.welcome_text_intro')!!}<br><br>
    {!!trans('page.welcome_text_content')!!}<br><br>
    {!!trans('page.welcome_reference_tables')!!}<br><br>
    {!!trans('page.welcome_text_sources')!!}<br><br>
    {!!trans('page.welcome_text_software')!!}
    {!!trans('page.welcome_who_can_use')!!}<br><br>
    {!!trans('page.welcome_logo')!!}<br><br>
@stop

