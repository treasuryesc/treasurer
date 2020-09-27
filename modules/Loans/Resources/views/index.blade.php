@extends('layouts.admin')

@section('content')
    <p>
        This view is loaded from module: {!! config('test.name') !!}
    </p>
@stop
