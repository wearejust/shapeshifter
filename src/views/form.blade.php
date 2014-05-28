@extends('shapeshifter::layouts.master')

@section('content')

<h1>{{ $title }}</h1>
@if (count($tabs) > 1)
<ul class="nav nav-tabs" data-tabs="tabs" id="tabs">
    @foreach ($tabs as $k=>$tab)
        <li><a href="#{{ Str::slug($k) }}" data-toggle="tab">{{ $k == '_default' ? 'General' : $k }}</a></li>
        @foreach ($tab['attributes'] as $attr)
        <li><a href="#{{ Str::slug($attr->getDestinationName()) }}" data-toggle="tab">{{ $attr->getDestinationName() }}</a></li>
        @endforeach
    @endforeach
</ul>
@endif

@if ( ! Notification::all()->isEmpty())
<div class="messages">
    {{ Notification::showAll() }}
</div>
@endif

{{ Form::model($model, array('class' => 'section-start','enctype' => 'multipart/form-data', 'method' => $mode == 'edit' ? 'PUT' : "POST", 'url' => route($routes[$mode == 'edit' ? 'update' : 'store'], array_merge($ids,array($model->id))))) }}
<fieldset class="section-start">
    <legend class="accessibility">{{ $title }}</legend>
    @foreach ($tabs as $k=>$tab)
        <div class="tab-pane" id="{{ Str::slug($k) }}">
            @foreach ($tab as $attr)
                @if (!Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr))
                    {{ $attr->display() }}
                @endif
            @endforeach
        </div>
    @endforeach
    <div class="offset-control-list">
        <ul class="control-list list group">
            <li class="control-item">{{ Form::submit( __('form.save'), array( 'class' => 'btn')) }}</li>
            <li class="control-item"><a class="btn btn-cancel" href="{{ $cancel }}">{{__('form.cancel')}}</a></li>
        </ul>
    </div>
</fieldset>
{{ Form::close() }}

@foreach ($tab as $attr)
    @if (Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr))
        {{ $attr->display() }}
    @endif
@endforeach

@stop
