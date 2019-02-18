@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-role-edit-page
@endsection

@section('page')

<trax-account-role-edit roleid="{{ $data->role_id }}"></trax-account-role-edit>

<div class="row pt-4">
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="info_outline" title="@lang('trax-ui::form.general_data')">
            <trax-account-role-edit-data></trax-account-role-edit-data>
        </trax-ui-card-icon-header>

    </div>
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="pan_tool" title="@lang('trax-account::common.permissions')">
            <trax-account-role-edit-permissions></trax-account-role-edit-permissions>
        </trax-ui-card-icon-header>

    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection
