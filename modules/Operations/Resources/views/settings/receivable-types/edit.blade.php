@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans('operations::general.receivable-types')]))

@section('content')
    <div class="card">
        {!! Form::open([
            'method' => 'PATCH',
            'id' => 'receivable-type',
            'route' => 'operations.settings.receivable-types.update',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}
        <div class="card-body">
            <div class="row">
                <div
                    class="form-group col-md-6 required"
                    :class="[{'has-error': form.errors.get(&quot;id&quot;) }]">
                    <label for="id" class="form-control-label">ID</label>

                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-id"></i>
                            </span>
                        </div>
                        <input readonly class="form-control" data-name="id" data-value="{{$receivabletype->id}}" placeholder="Digite ID" v-model="form.id" value="{{$receivabletype->id}}" required="required" name="id" type="text" id="id">
                    </div>
                    <div class="invalid-feedback d-block"
                         v-if="form.errors.has(&quot;id&quot;)"
                         v-html="form.errors.get(&quot;id&quot;)">
                    </div>
                </div>

                {{ Form::textGroup('name', trans('operations::general.description'), 'name', ['required' => 'required'], $receivabletype->name) }}

                {{ Form::textareaGroup('attributes_schema', trans('operations::general.schema'), 'attributes_schema', $receivabletype->attributes_schema, ['required' => 'required', 'rows' => 10]) }}

                {{ Form::hidden('company_id', session('company_id'), ['id' => 'company_id']) }}
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                {{ Form::saveButtons('operations.settings.receivable-types.index') }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var receivable_type_items = {!! (old('items')) ? json_encode(old('items')) : 'false' !!};
    </script>

    <script src="{{ asset('public/js/modules/operations/receivable-types.js?v=' . version('short')) }}"></script>
@endpush
