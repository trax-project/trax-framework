@extends('trax-ui::layouts.ext')

@section('body-class')
    password-page off-canvas-sidebar
@endsection

@section('page')

<div class="col-lg-5 col-md-8 col-sm-10 ml-auto mr-auto trax-no-required">

    <trax-ui-card-plain-header title="@lang('trax-account::common.choose_password')" header-color="{{ config('trax.ui.colors.auth') }}">
        <trax-account-auth-password-reset email="{{ $email or old('email') }}" token="{{ $token }}" color="{{ config('trax.ui.colors.auth') }}">
        </trax-account-auth-password-reset>
    </trax-ui-card-plain-header>

</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection
