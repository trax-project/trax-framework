
<nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute fixed-top">
    <div class="container-fluid align-items-start">
        <div class="navbar-wrapper align-items-start">
            <div class="navbar-minimize pt-1">
                <trax-ui-sidebar-toggle></trax-ui-sidebar-toggle>
            </div>
            @if (isset($nav))
                <div class="navbar-title">
                    <span class="navbar-brand">{{ $nav->title }}</span>
                    @if (isset($nav->breadcrumb))
                        @include('trax-ui::layouts.parts.int.breadcrumb')
                    @endif
                </div>
			@endif
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end pt-1">

            <ul class="navbar-nav">
				@include('trax-ui::layouts.parts.int.topmenu')
                <trax-notification-top-menu></trax-notification-top-menu>
				@include('trax-ui::layouts.parts.int.usermenu')
            </ul>
        </div>
    </div>
</nav>

