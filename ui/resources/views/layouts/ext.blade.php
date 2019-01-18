@extends('trax-ui::layouts.base')

@section('layout')
    
    @include('trax-ui::layouts.parts.ext.topbar')
    <div class="wrapper wrapper-full-page ext-page">
        <div class="page-header login-page header-filter" filter-color="black" style="background-image: url({{ asset(config('trax.ui.backgrounds.auth')) }}); background-size: cover; background-position: top center;">
            <div id="app" class="container page-container">
                @yield('page')
            </div>
            @include('trax-ui::layouts.parts.ext.footer')
        </div>
    </div>

@endsection

