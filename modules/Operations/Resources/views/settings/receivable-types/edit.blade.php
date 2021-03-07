@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans('operations::general.receivable-types')]))

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('operations.settings.receivable-types.update', ['receivabletype_id' => $receivabletype->id]) }}" accept-charset="UTF-8" id="receivable-type"
              role="form" class="form-loading-button" novalidate>
            @method('PATCH')
            @csrf
        <div class="card-body">
            <div class="row">
                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}

                <div class="form-group col-md-6 required @error('id') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.id')}}</label>
                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-tag"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="id" placeholder="Digite {{trans('operations::general.id')}}" required="required" name="id" type="text" id="id" value="@if(old('id')){{old('id')}}@else{{$receivabletype->id}}@endif">
                    </div>
                    @error('id')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-6 required @error('name') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.description')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="name" placeholder="Digite {{trans('operations::general.description')}}" required="required" name="name" type="text" id="name" value="@if(old('name')){{old('name')}}@else{{$receivabletype->name}}@endif">
                    </div>
                    @error('name')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-12 required @error('attributes_schema') has-error @enderror">
                    <label for="attributes_schema" class="form-control-label">{{ trans('operations::general.schema') }}</label>
                    <textarea class="form-control" data-name="attributes_schema" data-value="" placeholder="Digite {{ trans('operations::general.schema') }}" required="required" rows="10" name="attributes_schema" cols="50" id="attributes_schema">@if(old('attributes_schema')){{old('attributes_schema')}}@else{!!$receivabletype->attributes_schema !!}@endif</textarea>
                    @error('attributes_schema')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.settings.receivable-types.index') }}
            </div>
        </div>
        </form>
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var receivable_type_items = {!! (old('items')) ? json_encode(old('items')) : 'false' !!};
    </script>

    <script src="{{ asset('public/js/modules/operations/receivable-types.js?v=' . version('short')) }}"></script>
@endpush
