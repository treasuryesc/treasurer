@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('operations::general.loan-types')]))

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('operations.settings.loan-types.store') }}" accept-charset="UTF-8" id="loan-type"
              role="form" class="form-loading-button" novalidate>
            @csrf
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6 required @error('id') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.id')}}</label>
                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-tag"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="id" placeholder="Digite {{trans('operations::general.id')}}" required="required" name="id" type="text" id="id" value="{{old('id')}}">
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
                        <input class="form-control" data-name="name" placeholder="Digite {{trans('operations::general.description')}}" required="required" name="name" type="text" id="name" value="{{old('name')}}">
                    </div>
                    @error('name')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-12 required @error('attributes_schema') has-error @enderror">
                    <label for="attributes_schema" class="form-control-label">{{ trans('operations::general.schema') }}</label>
                    <textarea class="form-control" data-name="attributes_schema" data-value="" placeholder="Digite {{ trans('operations::general.schema') }}" required="required" rows="10" name="attributes_schema" cols="50" id="attributes_schema">{{ old('attributes_schema') }}</textarea>
                    @error('attributes_schema')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.settings.loan-types.index') }}
            </div>
        </div>
        </form>
{{--        {!! Form::close() !!}--}}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/operations/loan-types.js?v=' . version('short')) }}"></script>
@endpush
