@extends('trax-ui::layouts.base')

@section('layout')

    <div id="app" class="wrapper int-page">
        @include('trax-ui::layouts.parts.int.sidebar')
        <div class="main-panel">
            @include('trax-ui::layouts.parts.int.topbar')
            <div class="content pt-0">
                <div class="container-fluid">
                    <trax-notification-read></trax-notification-read>
                    @yield('page')
                </div>
            </div>
        </div>
    </div>

@endsection


