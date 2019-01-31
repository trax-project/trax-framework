@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-agreement-edit-page
@endsection

@section('page')

    <trax-account-agreement-edit></trax-account-agreement-edit>

@endsection

@section('components')
    <script src="{{ mix('js/trax-account.js') }}"></script>
@endsection
