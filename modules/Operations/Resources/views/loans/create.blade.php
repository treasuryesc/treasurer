@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans('operations::general.loans')]))

@section('content')
    <div class="card">
        <form method="GET" action="{{ route('operations.loans.create-step2') }}" accept-charset="UTF-8" id="loanpre"
              role="form" class="form-loading-button" novalidate>
        <div class="card-body">
            <div class="row">
                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}

                <div class="form-group col-md-6">
                    <akaunting-select class="required @error('type_id') has-error @enderror" icon="wrench" title="{{trans_choice('general.types', 1)}}" placeholder="- Selecionar {{trans_choice('general.types', 1)}} -" name="type_id" id="type_id" value="{{old('type_id')}}"
                        :options="{
                        @foreach($types as $type_id => $type_description)
                            &quot;{!! $type_id !!}&quot;:&quot;{!! $type_description !!}&quot;,
                        @endforeach
                        }"
                        @interface="form.errors.clear('type_id'); form.type_id = $event;"
                        :form-error="form.errors.get('type_id')"
                        no-data-text="Não existem dados"
                        no-matching-data-text="Não há dados correspondentes"
                    ></akaunting-select>
                @error('type_id')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                @enderror
                </div>

                <div class="form-group col-md-6">
                    <akaunting-select class="required @error('customer_id') has-error @enderror" icon="user" title="{{trans_choice('general.customers', 1)}}" placeholder="- Selecionar {{trans_choice('general.customers', 1)}} -" name="customer_id" id="customer_id" value="{{old('customer_id')}}"
                                      :options="{
                                      @foreach($customers as $customer_id => $customer_name)
                                              &quot;{!! $customer_id !!}&quot;:&quot;{!! $customer_name !!}&quot;,
                                      @endforeach
                                              }"
                                      @interface="form.errors.clear('customer_id'); form.customer_id = $event;"
                                      :form-error="form.errors.get('customer_id')"
                                      no-data-text="Não existem dados"
                                      no-matching-data-text="Não há dados correspondentes"
                    ></akaunting-select>
                    @error('customer_id')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>
            </div>
        </div>


        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::nextButtons('operations.loans.index') }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/operations/loanspre.js?v=' . version('short')) }}"></script>
@endpush
