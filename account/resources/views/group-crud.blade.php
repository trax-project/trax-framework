@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-group-crud-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-account-group-crud></trax-account-group-crud>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
