@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('operations::general.loan-types')]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'operations.settings.loan-types.store',
            'id' => 'loan-type',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}
        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('id', trans('operations::general.id'), 'id', ['required' => 'required']) }}

                {{ Form::textGroup('name', trans('operations::general.description'), 'name', ['required' => 'required']) }}

                {{ Form::textareaGroup('attributes_schema', trans('operations::general.schema'), 'attributes_schema', '', ['required' => 'required', 'rows' => 10]) }}

                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.settings.loan-types.index') }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var loan_type_items = {!! (old('items')) ? json_encode(old('items')) : 'false' !!};
    </script>

    <script src="{{ asset('public/js/modules/operations/loan-types.js?v=' . version('short')) }}"></script>
@endpush
