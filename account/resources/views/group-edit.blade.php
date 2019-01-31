@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-group-edit-page
@endsection

@section('page')

<div class="row pt-4">
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="search" title="@lang('trax-account::common.users')">
            <trax-account-group-edit-candidates group-id="{{ $data->group_id }}"></trax-account-group-edit-candidates>
        </trax-ui-card-icon-header>

    </div>
    <div class="col-lg-6">

        <trax-ui-card-icon-header icon="group" title="@lang('trax-account::common.group_members')">
            <trax-account-group-edit-members group-id="{{ $data->group_id }}"></trax-account-group-edit-members>
        </trax-ui-card-icon-header>

    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
