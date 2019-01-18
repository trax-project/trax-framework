@extends('trax-ui::layouts.ext')

@section('body-class')
    register-page off-canvas-sidebar
@endsection

@section('page')

<div class="col-lg-5 col-md-8 col-sm-10 ml-auto mr-auto">

    <trax-ui-card-plain-header title="@lang('trax-account::common.registration')" header-color="{{ config('trax.ui.colors.auth') }}">
        <trax-account-auth-register color="{{ config('trax.ui.colors.auth') }}" 
            firstname="{{ old('firstname') }}" lastname="{{ old('lastname') }}" 
            email="{{ old('email') }}" with-username="{{ config('trax-account.auth.username') }}">
        </trax-account-auth-register>
    </trax-ui-card-plain-header>

</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection

