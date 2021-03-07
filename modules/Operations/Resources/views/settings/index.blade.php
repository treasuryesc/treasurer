@extends('layouts.admin')

@section('title', trans('operations::general.settings'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @permission('read-operations-settings-loan-types')
                    <a href="{{route('operations.settings.loan-types.index')}}">Configurar Tipos de Empréstimos</a><br><br>
                    @endpermission
                    @permission('read-operations-settings-receivable-types')
                    <a href="{{route('operations.settings.receivable-types.index')}}">Configurar Tipos de Recebíveis</a><br><br>
                    @endpermission
                </div>

            </div>
        </div>
    </div>
@endsection
