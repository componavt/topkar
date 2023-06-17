<x-app-layout>
    <x-slot name="header">
        {{trans('page.site_title')}}
    </x-slot>

    {!!trans('page.welcome_text_intro')!!}<br><br>
    {!!trans('page.welcome_text_content')!!}<br><br>
    {!!trans('page.welcome_reference_tables')!!}<br><br>
    {!!trans('page.welcome_text_sources')!!}<br><br>
    {!!trans('page.welcome_text_software')!!}<br><br>
    {!!trans('page.welcome_logo')!!}<br><br>
    
    
</x-app-layout>
