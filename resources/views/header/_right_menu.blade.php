<nav id="right-menu" class="navbar navbar-default">
    @include('header._collapsed_hamburger')

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <ul class="nav navbar-nav">
            
            <li class="nav-item">
        @if ('en' == LaravelLocalization::getCurrentLocale())
                <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL('ru') }}" aria-expanded="false">TopKar in Russian</a>
        @else
                <a class="nav-link" href="{{ LaravelLocalization::getLocalizedURL('en') }}" aria-expanded="false">TopKar in English</a>
        @endif
            </li>
            <li class="nav-item dropdown" id='menu4'>
        @auth
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu" role="menu" id='menu2-sub'>
{{--                  <li><a class="dropdown-item" href="{{ route('profile.show') }}">{{ __('Profile') }}</a></li>
                @if (user_is_admin())
                  <li><a class="dropdown-item" href="{{ route('teams.show', Auth::user()->currentTeam->id) }}}">{{ __('Team Settings') }}</a></li>
                @endif --}}
                  <li><a class="dropdown-item" href="{{ route('logout') }}" 
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('navigation.log_out') }}</a>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                  </li>
                </ul>
        @else
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  {{ trans('navigation.enter_for_editors') }}
                </a>
                <ul class="dropdown-menu" role="menu" id='menu2-sub'>
            @if (session('status'))
                <div class="alert alert-success mb-3 rounded-0" role="alert">
                    {{ session('status') }}
                </div>
            @endif

                <form method="POST" class="user-enter" action="{{ route('login') }}">
                    @csrf
                    @include('widgets.form.formitem._text', 
                        ['name' => 'email', 
                         'attributes' => ['placeholder' => trans('navigation.email') ]])
                    @include('widgets.form.formitem._password', 
                        ['name' => 'password', 
                         'placeholder' => trans('navigation.password') ])
                         
                    <div class="user-submit">
                        <div class="remember-me">
                            <label><input name="remember" type="checkbox" hidden><span></span></label>
                            <span class="user-enter-remember-text">{{ __('general.remember_me') }}</span>
                        </div>
                        <div class="user-enter-submit">
                            @include('widgets.form.formitem._submit', ['title' => trans('general.log_in')])
                        </div>
                    </div>
                    <div>
{{--                        <div class="user-enter-add">
                            <div class="remember_me">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label for="remember_me">{{ __('general.remember_me') }}</label>
                            </div>

                        </div>                      
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase">
                        {{ __('general.log_in') }}
                    </button> --}}
                             
                    @if (Route::has('password.request'))
                    <p class="reset_password">
                        <a href="{{ route('password.request') }}">{{ __('general.reset_password') }}</a>
                    </p>
                    @endif                        
                </form>     
                </ul>
        @endauth
            </li>
        </ul>
    </div>
</nav>
