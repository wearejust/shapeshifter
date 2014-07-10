@extends('shapeshifter::layouts.master')

@section('content')

<h1>{{ $title }}</h1>

<div class="section">

    @if (count($tabs) > 1)
    <ul class="section section-sub section-end tab-list content-alt group">
        @foreach ($tabs as $k=>$tab)
        <li class="tab-list-item">
            <a class="tab-list-item-button" href="#{{ Str::slug($k) }}">{{ $k == '_default' ? 'Algemeen' : $k }}</a>
        </li>
        @endforeach
    </ul>
    @endif

    @if ( Notification::get('error')->first())
    <div class="messages">
        {{ Notification::showError() }}
    </div>
    @endif

    <div class="section section-start section-main">
        {{ Form::model($model, array('class' => 'section-start', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => $mode == 'edit' ? 'PUT' : "POST", 'url' => route($routes[$mode == 'edit' ? 'update' : 'store'], array_merge($ids,array($model->id))))) }}
            <div class="section">
                <fieldset class="section section-sub">
                    <legend class="accessibility">{{ $title }}</legend>
                    <div class="section">
                        @foreach ($tabs as $k=>$tab)
                            <div class="tab-pane" id="{{ Str::slug($k) }}">
                                @foreach ($tab as $attr)
                                    @if ( ! Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr) && !$attr->hasFlag('hide'))
                                        {{ $attr }}
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </fieldset>
                {{--
                <fieldset class="section section-sub">
                    <legend class="accent"><span class="" style="display: block; margin-bottom: 1.375rem;">Subkop</span></legend>
                    <div class="section">
                        @foreach ($tab as $attr)
                            @if (!Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr))
                                {{ $attr }}
                            @endif
                        @endforeach
                    </div>
                </fieldset>
                --}}
            </div>
            <div class="footer controls">
                <div class="controls-content">
                    <div class="content">
                        <ul class="control-list list">
                            <li class="control-item">
                                <button class="control-item-button btn btn-save" type="submit">{{ __('form.save') }}</button>
                            <!--</li>-->
                            <li class="control-item item-alt">
                                <button class="control-item-button btn btn-remove" type="button">Verwijderen</button>
                            <!--</li>-->
                            {{--<li class="control-item"><a class="btn btn-cancel" href="{{ $cancel }}">{{__('form.cancel')}}</a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>

    @foreach ($tab as $attr)
        @if (Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr) && !$attr->hasFlag('hide'))
            {{ $attr }}
        @endif
    @endforeach

    @stop
</div>