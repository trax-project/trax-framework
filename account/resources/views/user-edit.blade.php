@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-user-edit-page
@endsection

@section('page')

<trax-account-user-edit userid="{{ $data->user_id }}"></trax-account-user-edit>

<div class="row pt-4">
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="perm_identity" title="@lang('trax-account::common.personal_data')">
            <trax-account-user-edit-data 
                self-edit="{{ $user->id == $data->user_id }}" with-username="{{ config('trax-account.auth.username') }}">
            </trax-account-user-edit-data>
        </trax-ui-card-icon-header>

    </div>
    <div class="col-lg-6">

        <trax-ui-card-icon-header class="mb-5" icon="lock_outline" title="@lang('trax-account::common.access')">
            <trax-account-user-edit-access self-edit="{{ $user->id == $data->user_id }}" invitation="{{ config('trax-account.auth.invitation') }}">
            </trax-account-user-edit-access>
        </trax-ui-card-icon-header>

        <trax-ui-card-icon-header icon="pan_tool" title="@lang('trax-account::common.rights')">
            <trax-account-user-edit-rights self-edit="{{ $user->id == $data->user_id }}"
                manage-entities="{{ config('trax-account.services.entities') }}">
            </trax-account-user-edit-rights>
        </trax-ui-card-icon-header>

    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
