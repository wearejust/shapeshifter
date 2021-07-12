@extends('shapeshifter::layouts.master')

@section('content')

<h1 class="record-title">{{ $title }}</h1>

@if ($mode == 'edit' && $model->updated_at)
<div class="section section-start paragraph record-updated">
    <p class="section-start section-end quiet" style="font-size: 11px;">{{ __('form.updated_at') }}: {{ $model->updated_at->formatLocalized('%d %B %Y, %H:%M') }}</p>
</div>
@endif

<div class="section">



    @include('flash::message')

    @if ($form->getTabs()->count())
    <ul class="section section-sub section-end tab-list content-alt group">
        @foreach ($form->getTabs() as $tab)
        <li class="tab-list-item">
            <a class="tab-list-item-button" href="#{{ $tab->getSlug() }}">{{ $tab->getName() }}</a>
        </li>
        @endforeach
    </ul>
    @endif

    <div class="section section-start section-main">
        {!! Form::model($model, array('class' => 'section-start', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'method' => $mode == 'edit' ? 'PUT' : "POST", 'url' => route($routes[$mode == 'edit' ? 'update' : 'store'], array_merge($ids,array($model->id))))) !!}
        <div class="section">
            <fieldset class="section section-sub">
                <legend class="accessibility">{{ $title }}</legend>
                <div class="section">
                    @foreach ($form->getTabs() as $tab)
                    <div class="tab-pane" id="{{ $tab->getSlug() }}">
                        @foreach ($tab->getSections() as $section)
                        @include('shapeshifter::section', array('attributes' => $section->getAttributes()))
                        @endforeach
                        @include('shapeshifter::attribute', array('attributes' => $tab->getAttributes(), 'model' => $model))
                    </div>
                    @endforeach

                    @foreach($form->getSections() as $section)
                    @include('shapeshifter::section', array('attributes' => $section->getAttributes()))
                    @endforeach

                    @include('shapeshifter::attribute', array('attributes' => $form->getAttributes(), 'model' => $model))
                </div>
            </fieldset>
            @yield('extra')
        </div>
        <div class="footer controls">
            <div class="controls-content">
                <div class="content container">
                    <ul class="control-list list">
                        <li class="control-item" style="position: absolute; left: 0;">
                            <button class="control-item-button btn btn-save js-required-target" type="submit">{{ __('form.save') }}</button>
                            <!--</li>-->
                            {{--<li class="control-item"><a class="btn btn-cancel" href="{{ $cancel }}">{{__('form.cancel')}}</a>
                        </li>--}}
                    </ul>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

        @if ($mode == 'edit' && $currentUser->can('delete') && ! in_array($model->id, $disableDeleting))
        <div class="footer controls" style="min-height: 0; background-color: transparent; overflow: visible;">
            <div class="controls-content" style="padding: 0;">
                <div class="content container">
                    <div class="js-remove-wrapper" style="bottom: 0; position: absolute; right: 0; z-index: 1000;">
                        {!! Form::model($model, array('method' => 'DELETE', 'url' => route($routes['destroy'], $ids))) !!}
                        <div class="controls-content">
                            <button class="control-item-button btn btn-remove confirm-delete-dialog" type="submit">{{__('form.remove') }}</button>
                        </div>
                        {!! Form::close() !!}
                        <div class="dialog-confirm" style="display: none;">
                            <p>{{ __('dialog.remove') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @foreach ($form->getTabs() as $tab)
    <div id="{{ $tab->getSlug() }}-extra">
        @include('shapeshifter::relation', array('attributes' => $tab->getAttributes()))
    </div>
    @endforeach

    @include('shapeshifter::relation', array('attributes' => $form->getAttributes()))

    @stop
</div>