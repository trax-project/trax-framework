@extends('trax-ui::layouts.ext')

@section('body-class')
    login-page off-canvas-sidebar
@endsection

@section('page')

<div class="col-lg-5 col-md-8 col-sm-10 ml-auto mr-auto trax-no-required">

    <trax-ui-card-plain-header title="@lang('trax-account::common.login')" header-color="{{ config('trax.ui.colors.auth') }}">
        <trax-account-auth-login email="{{ old('email') }}" color="{{ config('trax.ui.colors.auth') }}"
            remember="{{ old('remember') }}" can-remember="{{ config('trax-account.auth.remember') }}" 
            with-username="{{ config('trax-account.auth.username') }}">
        </trax-account-auth-login>
    </trax-ui-card-plain-header>

</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection


                        