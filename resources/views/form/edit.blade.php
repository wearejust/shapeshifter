@extends('shapeshifter::layouts.master')

@section('content')
    <h1 class="record-title">{{ $title }}</h1>
    <div class="section section-start paragraph record-updated">
        <p class="section-start section-end quiet" style="font-size: 11px;">{{ __('form.updated_at') }}
            : {{ $model->updated_at->formatLocalized('%d %B %Y, %H:%M') }}</p>
    </div>
    @include('shapeshifter::form.base')
@stop
