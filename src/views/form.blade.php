@extends('shapeshifter::layouts.master')

@section('content')

<h1>{{ $title }}</h1>

@if ($mode == 'edit' && $model->updated_at)
<div class="section section-start section-sub" style="position: absolute; right: 2.75rem; top: 71px;">
    <dl class="quiet" style="font-size: 11px;">
        <dt style="float: left; margin-right: 2em;">{{ __('form.updated_at') }}</dt>
        <dd style="display: block; overflow: hidden;">{{ $model->updated_at->formatLocalized('%d %B %Y %H:%M') }}
        </dd>
    </dl>
</div>
@endif

<div class="section">

    @if ($form->getTabs()->count())
    <ul class="section section-sub section-end tab-list content-alt group">
        @foreach ($form->getTabs() as $tab)
        <li class="tab-list-item">
            <a class="tab-list-item-button" href="#{{ $tab->getSlug() }}">{{ $tab->getName() }}</a>
        </li>
        @endforeach
    </ul>
    @endif

    @if ( Notification::get('error')->first())
    <div class="messages">
        <div class=" alert alert-error">
            <ul style="margin:-1.375em 0;">
                @foreach (Notification::get('error') as $error)
                    <li>{{ $error->getMessage() }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="section section-start section-main">
        {{ Form::model($model, array('class' => 'section-start', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => $mode == 'edit' ? 'PUT' : "POST", 'url' => route($routes[$mode == 'edit' ? 'update' : 'store'], array_merge($ids,array($model->id))))) }}
            <div class="section">
                <fieldset class="section section-sub">
                    <legend class="accessibility">{{ $title }}</legend>
                    <div class="section">
                        @foreach ($form->getTabs() as  $tab)
                            <div class="tab-pane" id="{{ $tab->getSlug() }}">
                                @foreach ($tab->getSections() as $section)
                                    @include('shapeshifter::section', array('attributes' => $section->getAttributes()))
                                @endforeach
                                @include('shapeshifter::attribute', array('attributes' => $tab->getAttributes()))
                            </div>
                        @endforeach

                        @foreach($form->getSections() as $section)
                            @include('shapeshifter::section', array('attributes' => $section->getAttributes()))
                        @endforeach

                        @include('shapeshifter::attribute', array('attributes' => $form->getAttributes()))
                    </div>
                </fieldset>
            </div>
            <div class="footer controls">
                <div class="controls-content">
                    <div class="content container">
                        <ul class="control-list list">
                            <li class="control-item" style="position: absolute; left: 0;">
                                <button class="control-item-button btn btn-save js-required-target" type="submit">{{ __('form.save') }}</button>
                            <!--</li>-->
                            {{--<li class="control-item"><a class="btn btn-cancel" href="{{ $cancel }}">{{__('form.cancel')}}</a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        {{ Form::close() }}

        @if ($mode == 'edit' && $currentUser->can('delete') && ! in_array($model->id, $disableDeleting))
        <div class="footer controls" style="min-height: 0; z-index: 1000; background-color: transparent; overflow: visible;">
            <div class="controls-content" style="padding: 0;">
                <div class="content container">
                    <div class="js-remove-wrapper" style="bottom: 0; position: absolute; right: 0; z-index: 1000;">
                        {{ Form::model($model, array('method' => 'DELETE', 'url' => route($routes['destroy'], $ids))) }}
                            {{ Form::submit(__('form.remove'), array('class' => 'control-item-button btn btn-remove confirm-delete-dialog')) }}
                        {{ Form::close() }}
                        <div class="dialog-confirm" style="display: none;">
                            <p>{{ __('dialog.remove') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @foreach ($form->getTabs() as  $tab)
        <div id="{{ $tab->getSlug() }}-extra">
            @include('shapeshifter::relation', array('attributes' => $tab->getAttributes()))
        </div>
    @endforeach

    @include('shapeshifter::relation', array('attributes' => $form->getAttributes()))

    @stop
</div>
