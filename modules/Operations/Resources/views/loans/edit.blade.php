@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans('operations::general.loans')]))

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('operations.loans.update', ['loan_id' => $loan->id]) }}" accept-charset="UTF-8" id="loan"
              role="form" class="form-loading-button" novalidate>
            @method('PATCH')
            @csrf
        <div class="card-body">
            <div class="row">
                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}
                {{ Form::hidden('type_id', $loan->type_id) }}
                {{ Form::hidden('customer_id', $loan->customer_id) }}

                <div class="form-group col-md-6">
                    <label class="form-control-label">{{trans_choice('general.types', 1)}}</label>
                    <div class="input-group input-group-merge ">
                        {{$loan->type->name}}
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="form-control-label">{{trans_choice('general.customers', 1)}}</label>
                    <div class="input-group input-group-merge ">
                        {{$loan->customer->name}}
                    </div>
                </div>

                <div class="form-group col-md-6 required @error('contract') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.contract')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="contract" placeholder="Digite {{trans('operations::general.contract')}}" required="required" name="contract" type="text" id="contract" value="@if(old('contract')){{old('contract')}}@else{{$loan->contract}}@endif">
                    </div>
                    @error('contract')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <akaunting-select class="required @error('status_id') has-error @enderror" icon="" title="{{trans('operations::general.status')}}" placeholder="- Selecionar {{trans('operations::general.status')}} -" name="status_id" id="status_id" value="@if(old('status_id')){{old('status_id')}}@else{{$loan->status_id}}@endif"
                                      :options="{
                                      @foreach($status as $status_id => $status_description)
                                              &quot;{!! $status_id !!}&quot;:&quot;{!! $status_description !!}&quot;,
                                      @endforeach
                                              }"
                                      @interface="form.errors.clear('status_id'); form.status_id = $event;"
                                      :form-error="form.errors.get('status_id')"
                                      no-data-text="Não existem dados"
                                      no-matching-data-text="Não há dados correspondentes"
                    >
                    </akaunting-select>
                    @error('status_id')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <akaunting-select class="required @error('account_id') has-error @enderror" icon="" title="{{trans('operations::general.account')}}" placeholder="- Selecionar {{trans('operations::general.account')}} -" name="account_id" id="account_id" value="@if(old('account_id')){{old('account_id')}}@else{{$loan->account_id}}@endif"
                                      :options="{
                                      @foreach($accounts as $account_id => $account_description)
                                              &quot;{!! $account_id !!}&quot;:&quot;{!! $account_description !!}&quot;,
                                      @endforeach
                                              }"
                                      @interface="form.errors.clear('account_id'); form.account_id = $event;"
                                      :form-error="form.errors.get('account_id')"
                                      no-data-text="Não existem dados"
                                      no-matching-data-text="Não há dados correspondentes"
                    >
                    </akaunting-select>
                    @error('account_id')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 required @error('amortizations') has-error @enderror">
                    <label for="amortizations" class="form-control-label">{{trans('operations::general.amortizations')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="amortizations" placeholder="Digite {{trans('operations::general.amortizations')}}" required="required" name="amortizations" type="number" id="amortizations" value="@if(old('amortizations')){{old('amortizations')}}@else{{$loan->amortizations}}@endif">
                    </div>

                    @error('amortizations')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 required @error('interest_rate') has-error @enderror">
                    <label for="interest_rate" class="form-control-label">{{trans('operations::general.interest_rate')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="interest_rate" placeholder="Digite {{trans('operations::general.interest_rate')}}" required="required" name="interest_rate" type="number" id="interest_rate" value="@if(old('interest_rate')){{old('interest_rate')}}@else{{$loan->interest_rate}}@endif">
                    </div>

                    @error('interest_rate')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 required @error('amount') has-error @enderror">
                    <label for="amount" class="form-control-label">{{trans('operations::general.amount')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="amount" placeholder="Digite {{trans('operations::general.amount')}}" required="required" name="amount" type="number" id="amount" value="@if(old('amount')){{old('amount')}}@else{{$loan->amount}}@endif">
                    </div>

                    @error('amount')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 required @error('due') has-error @enderror">
                    <label for="due" class="form-control-label">{{trans('operations::general.due')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="due" placeholder="Digite {{trans('operations::general.due')}}" required="required" name="due" type="number" id="due" value="@if(old('due')){{old('due')}}@else{{$loan->due}}@endif">
                    </div>

                    @error('due')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 required @error('contract_at') has-error @enderror">
                    <label for="contract_at" class="form-control-label">{{trans('operations::general.contract_at')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="contract_at" placeholder="Digite {{trans('operations::general.contract_at')}}" required="required" name="contract_at" type="date" id="contract_at" @if(old('contract_at')) value="{{old('contract_at')}}" @else @if(isset($loan->contract_at)) value="{{$loan->contract_at->format('Y-m-d')}}" @endif @endif>
                    </div>

                    @error('contract_at')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-3 @error('lent_at') has-error @enderror">
                    <label for="lent_at" class="form-control-label">{{trans('operations::general.lent_at')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="lent_at" placeholder="Digite {{trans('operations::general.lent_at')}}" name="lent_at" type="date" id="lent_at" @if(old('lent_at')) value="{{old('lent_at')}}" @else @if(isset($loan->lent_at)) value="{{$loan->lent_at->format('Y-m-d')}}" @endif @endif>
                    </div>

                    @error('lent_at')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>


                <div class="form-group col-md-3 @error('last_at') has-error @enderror">
                    <label for="last_at" class="form-control-label">{{trans('operations::general.last_at')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="last_at" placeholder="Digite {{trans('operations::general.last_at')}}" name="last_at" type="date" id="last_at" @if(old('last_at')) value="{{old('last_at')}}" @else @if(isset($loan->last_at)) value="{{$loan->last_at->format('Y-m-d')}}" @endif @endif>
                    </div>

                    @error('last_at')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-6 required @error('reference') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.reference')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="reference" placeholder="Digite {{trans('operations::general.reference')}}" required="required" name="reference" type="text" id="reference" value="@if(old('reference')){{old('reference')}}@else{{$loan->reference}}@endif">
                    </div>
                    @error('reference')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-6 required @error('index') has-error @enderror">
                    <label for="id" class="form-control-label">{{trans('operations::general.index')}}</label>
                    <div class="input-group input-group-merge ">
                        <input class="form-control" data-name="index" placeholder="Digite {{trans('operations::general.index')}}" required="required" name="index" type="text" id="index" value="@if(old('index')){{old('index')}}@else{{$loan->index}}@endif">
                    </div>
                    @error('index')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                <div class="form-group col-md-12 @error('notes') has-error @enderror">
                    <label for="notes" class="form-control-label">{{trans('operations::general.notes')}}</label>
                    <textarea class="form-control" data-name="notes" placeholder="Digite {{trans('operations::general.notes')}}" rows="5" name="notes" cols="50" id="notes">@if(old('notes')){!! old('notes') !!}@else{!! $loan->notes !!}@endif</textarea>
                    @error('notes')
                    <div class="invalid-feedback d-block">
                        {!! $message !!}
                    </div>
                    @enderror
                </div>

                @foreach($additional_fields as $additional_field)

                    <div class="form-group col-md-12 @if($additional_field['required'] == 'required') required @endif @error($additional_field['attribute']) has-error @enderror">
                        <label for="id" class="form-control-label">{{$additional_field['attribute']}}</label>
                        <div class="input-group input-group-merge ">
                            <input class="form-control" data-name="{{$additional_field['attribute']}}" placeholder="Digite {{$additional_field['attribute']}}" @if($additional_field['required'] == 'required') required="required" @endif name="{{$additional_field['attribute']}}" @if($additional_field['type'] == 'number') type="number" @else type="text" @endif id="{{$additional_field['attribute']}}" value="@if(old($additional_field['attribute'])){{old($additional_field['attribute'])}}@else{{$additional_field['value']}}@endif">
                        </div>
                        @error($additional_field['attribute'])
                        <div class="invalid-feedback d-block">
                            {!! $message !!}
                        </div>
                        @enderror
                    </div>

                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.loans.index') }}
            </div>
        </div>
        </form>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/operations/loans.min.js?v=' . version('short')) }}"></script>
@endpush
