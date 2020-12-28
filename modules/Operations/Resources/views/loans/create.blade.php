@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('operations::general.loans')]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'operations.loans.store',
            'id' => 'loan',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}
        <div class="card-body">
            <div class="row">
                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}

                {{ Form::selectGroup('customer_id', 'Cliente', null, $customers, null, ['required' => 'required'], 'col-md-6', null) }}

                {{ Form::textGroup('contract', 'Contrato', 'contract', ['required' => 'required']) }}

                {{ Form::selectGroup('type_id', 'Tipo', null, $types, null, ['required' => 'required'], 'col-md-6', null) }}

                {{ Form::selectGroup('status_id', 'Status', null, $status, null, ['required' => 'required'], 'col-md-6', null) }}

                {{ Form::selectGroup('account_id', 'Conta', null, $accounts, null, ['required' => 'required'], 'col-md-3', null) }}

                {{ Form::numberGroup('amortizations', 'Parcelas', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::numberGroup('interest_rate', 'Tx Juros', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::numberGroup('amount', 'Valor', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::numberGroup('due', 'Sd. Devedor', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::dateGroup('contract_at', 'Data Contrato', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::dateGroup('lent_at', 'Data Liberação', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::dateGroup('last_at', 'Data Último', null, ['required' => 'required'], null, 'col-md-3', null) }}

                {{ Form::textGroup('references', 'Referência', null, ['required' => 'required'], null, 'col-md-6', null) }}

                {{ Form::textGroup('index', 'Índice de Reajuste', null, ['required' => 'required'], null, 'col-md-6', null) }}

                {{ Form::textareaGroup('notes', 'Observações', null, null, ['rows' => '5'], 'col-md-12', null) }}

                <div id="customFields">
                    CAMPOS PERSONALIZADOS, CONFORME SELEÇÃO DO TIPO DE EMPRÉSTIMO (AINDA EM ANDAMENTO)
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.loans.index') }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var loans_items = {!! (old('items')) ? json_encode(old('items')) : 'false' !!};
    </script>

    <script src="{{ asset('public/js/modules/operations/loans.js?v=' . version('short')) }}"></script>
@endpush
