@extends('shapeshifter::layouts.master')

@section('content')
    <h1 class="record-title">{{ $title }}</h1>
    @include('shapeshifter::form.base')
@stop
