@extends('shapeshifter::layouts.master')

@section('content')

<h1 class="record-title">{{ $title }}</h1>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsdiff/3.0.0/diff.js"></script>

@if ($mode == 'edit' && $model->updated_at)
<div class="section section-start paragraph record-updated">

    @if(method_exists($model, 'revisionHistory'))
        <p class="section-start section-end quiet" style="font-size: 11px;">{{ __('form.update_revisions', ['date' => $model->updated_at->formatLocalized('%d %B %Y, %H:%M'), 'user' => $model->revisionHistory->first()->userResponsible()->first_name]) }}</p>
        <p class="section-start section-end quiet" style="font-size: 11px;"><a href="#history-summary" class="js-popup-view"><i class="fa fa-clock-o"></i> {{ __('form.btn_revision') }} ({{ $model->revisionHistory->count()  }})</a></p>
        <div class="js-hide">
            <div class="history-view" id="history-summary">
            @if($model->revisionHistory->first()->key == 'created_at' && !$model->revisionHistory->first()->old_value)
                <p>{{ $model->revisionHistory->first()->userResponsible()->first_name }} created this resource at {{ $model->revisionHistory->first()->newValue() }}</p>
            @endif

            @if($model->revisionHistory->count() > 0)
            <table>
                <thead>
                    <th>User</th>
                    <th>Field</th>
                    <th>Option</th>
                </thead>

                @foreach($model->revisionHistory as $history)
                    <tr>
                        <td>{{ $history->userResponsible()->first_name }}</td>
                        <td>{{ translateAttribute($history->fieldName()) }}</td>
                        <td><a href="#view-diff-{{ $history->id }}" class="js-popup-view"><i class="fa fa-search"></i></a></td>
                    </tr>
                @endforeach
            </table>
                @foreach($model->revisionHistory as $history)
                    <div class="js-hide">
                        <div id="view-diff-{{ $history->id }}">

                            <a href="#history-summary" class="js-popup-view btn btn-alt"> Back to overview</a>
                            <div id="display-diff-{{ $history->id }}"></div>

                            <script>
                                var diff = JsDiff.diffChars("{!! $history->oldValue() !!}", "{!! $history->newValue() !!}"),
                                    display = document.getElementById('display-diff-{{ $history->id }}'),
                                    fragment = document.createDocumentFragment();

                                diff.forEach(function(part){
                                    color = part.added ? 'green' :
                                            part.removed ? 'red' : 'grey';
                                    span = document.createElement('span');
                                    span.style.color = color;
                                    span.appendChild(document
                                            .createTextNode(part.value));
                                    fragment.appendChild(span);
                                });

                                display.appendChild(fragment);
                            </script>

                        </div>
                    </div>
                @endforeach
            @else
                <p>{{ __('form.no_revisions') }}</p>
            @endif
        </div>
        </div>
    @else
        <p class="section-start section-end quiet" style="font-size: 11px;">{{ __('form.updated_at') }}: {{ $model->updated_at->formatLocalized('%d %B %Y, %H:%M') }}</p>
    @endif
</div>
@endif

<div class="section">

    @if ( Notification::get('error')->first())
    <div class="messages">
        <div class="alert alert-error">
            <ul class="section-start section-end">
                @foreach (Notification::get('error') as $error)
                <li class="section">{{ $error->getMessage() }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

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
                        @foreach ($form->getTabs() as  $tab)
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
                        </ul>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}

        @if ($currentUser->can('delete') && ! in_array($model->id, $disableDeleting))
        <div class="footer controls" style="min-height: 0; background-color: transparent; overflow: visible;">
            <div class="controls-content" style="padding: 0;">
                <div class="content container">
                    <div class="js-remove-wrapper" style="bottom: 0; position: absolute; right: 0; z-index: 1000;">
                        {!! Form::model($model, array('method' => 'DELETE', 'url' => route($routes['destroy'], $ids)))  !!}
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

    @foreach ($form->getTabs() as  $tab)
        <div id="{{ $tab->getSlug() }}-extra">
            @include('shapeshifter::relation', array('attributes' => $tab->getAttributes()))
        </div>
    @endforeach

    @include('shapeshifter::relation', array('attributes' => $form->getAttributes()))

    @stop
</div>
