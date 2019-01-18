@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-my-profile-page
@endsection

@section('page')

<trax-account-user-edit userid="{{ $user->id }}"></trax-account-user-edit>

<div class="row">
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="perm_identity" title="@lang('trax-account::common.personal_data')">
            <trax-account-user-edit-data self-edit="1" with-username="{{ config('trax-account.auth.username') }}">
            </trax-account-user-edit-data>
        </trax-ui-card-icon-header>

    </div>
    <div class="col-lg-6">

        <trax-ui-card-icon-header class="mb-5" icon="lock_outline" title="@lang('trax-account::common.access')">
            <trax-account-user-edit-password></trax-account-user-edit-password>
        </trax-ui-card-icon-header>

    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
