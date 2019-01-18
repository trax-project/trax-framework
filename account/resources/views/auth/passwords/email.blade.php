@extends('trax-ui::layouts.ext')

@section('body-class')
    password-page off-canvas-sidebar
@endsection

@section('page')

<div class="col-lg-5 col-md-8 col-sm-10 ml-auto mr-auto">

    <trax-ui-card-plain-header title="@lang('trax-account::common.forgot_password_q')" header-color="{{ config('trax.ui.colors.auth') }}">
        <trax-account-auth-password-email email="{{ old('email') }}" color="{{ config('trax.ui.colors.auth') }}"></trax-account-auth-password-email>
    </trax-ui-card-plain-header>

</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection

