<!-- @ extends('navigation-menu')
@ section('logo-and-menu-top')-->

            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard menu top button') }}
                    </x-jet-nav-link>
                </div>
            </div>
<!-- @ stop -->

<!-- x-jet-nav-link href="{ { route('regions') } }" :active="request()->routeIs('dict-regions')">
                        { { __('dict-regions') } }
                    </x-jet-nav-link -->