@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-agreement-approve-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-account-agreement approve-button="1"></trax-account-agreement>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
