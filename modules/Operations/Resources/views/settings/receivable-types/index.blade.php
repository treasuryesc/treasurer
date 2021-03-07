@extends('layouts.admin')

@section('title', trans('operations::general.receivable-types'))

@section('new_button')
    @permission('create-operations-settings-receivable-types')
    <span><a href="{{ route('operations.settings.receivable-types.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    @endpermission
@endsection

@section('content')
    @if ($receivabletypes->count())
        <div class="card">
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-3 d-none d-md-block text-right">@sortablelink('id', trans('operations::general.id'))</th>
                        <th class="col-6 text-left">@sortablelink('name', trans('operations::general.description'))</th>
                        <th class="col-3 text-center"><a>{{ trans('general.actions') }}</a></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($receivabletypes as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-3 d-none d-md-block text-right">{{ $item->id }}</td>
                            <td class="col-6 text-left">{{ $item->name }}</td>
                            <td class="col-3 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @permission('update-operations-settings-receivable-types')
                                        <a class="dropdown-item" href="{{ route('operations.settings.receivable-types.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @endpermission
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.admin.pagination', ['items' => $receivabletypes])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'operations.settings.receivable-types', 'docs_path' => 'operations/settings/receivable-types'])
    @endif
@endsection

<script src="{{ asset('public/js/modules/operations/receivable-types.js?v=' . version('short')) }}"></script>
