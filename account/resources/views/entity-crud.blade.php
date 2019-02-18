@extends('trax-ui::layouts.int')

@section('body-class')
    trax-account-entity-crud-page
@endsection

@section('page')

<div class="row">
    <div class="col-12">
        <trax-ui-card>
            <trax-account-entity-crud></trax-account-entity-crud>
        </trax-ui-card>
    </div>
</div>

@endsection

@section('components')
    <script src="{{ traxMix('js/trax-account.js') }}"></script>
@endsection
