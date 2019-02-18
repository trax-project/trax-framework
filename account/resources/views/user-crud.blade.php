@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-user-crud-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-account-user-crud with-username="{{ config('trax-account.auth.username') }}">
        </trax-account-user-crud>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection
