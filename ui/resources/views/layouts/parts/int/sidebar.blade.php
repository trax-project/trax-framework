<div class="sidebar" data-background-color="black">

    <div class="logo">
        @include('trax-ui::layouts.parts.int.sidebar-logo')
    </div>

    <div class="sidebar-wrapper">
        @if(config('trax.ui.menu.multilevel'))
        <ul class="nav" id="sidemenu">
        @else
        <ul class="nav trax-menu-flat" id="sidemenu">
        @endif

        @include('trax-ui::layouts.parts.int.sidebar-menu')

        </ul>
    </div>
</div>
