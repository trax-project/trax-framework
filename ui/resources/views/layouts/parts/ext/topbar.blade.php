
<nav class="navbar navbar-expand-lg bg-primary navbar-transparent navbar-absolute ext-page" color-on-scroll="500">
    <div class="container">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="{{ url('login') }}">
                {{ config('app.name') }}
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar">
            <ul class="navbar-nav">

                @if(config('trax-account.auth.enabled'))

                    <li class="nav-item login">
                        <a href="{{ url('login') }}" class="nav-link">
                            <i class="material-icons">fingerprint</i> @lang('trax-account::common.login')
                        </a>
                    </li>

                    @if(config('trax-account.auth.registration'))
                    <li class="nav-item register">
                        <a href="{{ url('register') }}" class="nav-link">
                            <i class="material-icons">person_add</i> @lang('trax-account::common.register')
                        </a>
                    </li>
                    @endif

                    @if(config('trax-account.auth.password-reset'))
                    <li class="nav-item password">
                        <a href="{{ url('password/reset') }}" class="nav-link">
                            <i class="material-icons">lock_open</i> @lang('trax-account::common.password')
                        </a>
                    </li>
                    @endif

                @endif

                <li class="nav-item">
                    @if (app()->getLocale() == 'fr')
                        <a href="{{ url()->current().'?lang=en' }}" class="nav-link">
                            <i class="material-icons">outlined_flag</i> @lang('trax-ui::lang.english')
                        </a>
                    @else 
                        <a href="{{ url()->current().'?lang=fr' }}" class="nav-link">
                            <i class="material-icons">outlined_flag</i> @lang('trax-ui::lang.french')
                        </a>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>


