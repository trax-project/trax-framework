@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-role-crud-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-account-role-crud></trax-account-role-crud>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection
