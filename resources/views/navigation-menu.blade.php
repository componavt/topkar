    <!--nav class="navbar navbar-expand-md navbar-light bg-white border-bottom sticky-top"-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">TopKar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar: dictionary, ...  -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('toponyms.index') }}" role="button">
                  {{ trans('navigation.toponyms') }}
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ trans('navigation.auxiliaries') }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="{{ route('regions.index') }}">{{ trans('navigation.regions') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('districts1926.index') }}">{{ trans('navigation.districts_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('selsovets1926.index') }}">{{ trans('navigation.selsovets_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('settlements1926.index') }}">{{ trans('navigation.settlements_1926') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('geotypes.index') }}">{{ trans('navigation.geotypes') }}</a></li>
                </ul>
              </li>
            </ul>
            
            <!-- Right side Of Navbar: search -->
            <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>&thinsp;
            </form>
        </div>
            

            <!-- Right Side Of Navbar -- User and Team-->
            <div class="navbar-nav align-items-baseline">
                <!-- Settings Dropdown -->
                @auth
                    <x-jet-dropdown id="settingsDropdown">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="rounded-circle" width="32" height="32" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                {{ Auth::user()->name }}

                                <svg class="ms-2" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <h6 class="dropdown-header small text-muted">
                                {{ __('Manage Account') }}
                            </h6>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <hr class="dropdown-divider">

                            
                            
                            <!-- Teams sub-menu -->
                            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    
                                <!-- Team Management un-clickable faded header -->
                                <h6 class="dropdown-header">
                                    {{-- __('Manage Team') --}}
                                    {{ Auth::user()->currentTeam->name }}
                                </h6>

                                <!-- Team Settings -->
                                <x-jet-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                    {{ __('Team Settings') }}
                                </x-jet-dropdown-link>

                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                        {{ __('Create New Team') }}
                                    </x-jet-dropdown-link>
                                @endcan

                                <hr class="dropdown-divider">

                                <!-- Team Switcher -->
                                <h6 class="dropdown-header">
                                    {{ __('Switch Teams') }}
                                </h6>

                                @foreach (Auth::user()->allTeams() as $team)
                                    <x-jet-switchable-team :team="$team" />
                                @endforeach

                                <hr class="dropdown-divider">
                            @endif <!-- eo Teams sub-menu -->
                            
                            
                            
                            <!-- User Authentication -->
                            <x-jet-dropdown-link href="{{ route('logout') }}"
                                                 onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                {{ __('Log out') }}
                            </x-jet-dropdown-link>
                            <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                @csrf
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                @endauth
            </div><!-- eo of old ul -->
            
            <!-- Language switcher in navigation bar (right side) -->
            <ul class="nav navbar-nav navbar-right lang">
                @include('header.lang_switch')
            </ul>
        </div>
    </div>
</nav>