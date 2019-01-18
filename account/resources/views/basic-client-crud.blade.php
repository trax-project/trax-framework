@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-basic-client-crud-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-account-basic-client-crud></trax-account-basic-client-crud>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
