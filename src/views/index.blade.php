@extends('shapeshifter::layouts.master')

@section('content')
<div class="content-body-inner">
    <h1>{{ $title }}</h1>
    <div class="group">
        @if (userCanCreate($disabledActions))
        <div class="add-item">
            <a class="btn btn-default add-item-button" href="{{ route($routes['create'], $ids) }}" id="add-item">{{ __('form.create') }}</a>
        </div>
        @endif
        @if (count($records))
        <div class="filter-search">
            <form action="" class="section-start section-end" method="get">
                <fieldset>
                    <label class="accessibility" for="search">{{ __('form.search') }}</label>
                    <input class="search-control" id="search" placeholder="{{ __('form.search') }}…" type="search">
                    <button class="js-hide" type="submit">{{ __('form.search') }}</button>
                </fieldset>
            </form>
        </div>
        @endif
    </div>

    <div class="paragraph section-end" id="datatable">
        @if (!count($records))
        <div class="paragraph section-end">
            <div class="form-group">
                <p>{{ __('form.no-records') }}</p>
            </div>
        </div>
        @else
        <div class="content-alt">
            <div class="data-wrapper">
                <table class="section-sub section-start js-datatable {{ userCanDrag($disabledActions) ? 'js-datatable-order' : 'js-datatable-sortable' }}">
                    <thead>
                        @foreach ($attributes as $attr)
                        @if ( ! $attr->hasFlag('hide_list'))
                        <th class="table-header {{ ! userCanDrag($disabledActions) && $lastVisibleAttribute == $attr ? 'table-header-last' : '' }}">
                            <div class="container">
                                @if ( ! userCanDrag($disabledActions))
                                <span class="table-header-sort">
                                    <span class="table-header-sort-item table-header-sort-item-asc"><span class="accessibility">Oplopend</span></span>
                                    <span class="table-header-sort-item table-header-sort-item-desc"><span class="accessibility">Aflopend</span></span>
                                </span>
                                @endif
                                {{ translateAttribute($attr->name) }}
                            </div>
                        </th>
                        @endif
                        @endforeach
                        @if (userCanDrag($disabledActions))
                        <th class="table-header table-order js-disable-sort table-header-last"></th>
                        @endif
                        @if (userCanDelete($disabledActions))
                        <th class="table-header table-control js-disable-sort"></th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($records as $rec)
                        <tr class="table-row table-actions js-transform" data-edit-href="{{ route($routes['edit'], array_merge($ids, array($rec->id))) }}" data-record-id="{{ $rec->id }}">
                            @foreach ($attributes as $attr)
                                @if ( ! $attr->hasFlag('hide_list'))
                                <td class="table-cell {{ ! userCanDrag($disabledActions) && $lastVisibleAttribute == $attr ? 'table-cell-last' : '' }}">{{ $rec->{$attr->name} }}</td>
                                @endif
                            @endforeach
                            @if (userCanDrag($disabledActions))
                            <td class="table-cell table-order table-cell-last">
                                <button class="js-sortable-handle tricon link-alt item-alt" type="button"><span class="accessibility">Verplaatsen</span></button>
                            </td>
                            @endif
                            @if (userCanDelete($disabledActions))
                            <td class="table-cell table-control">
                                <div class="container">
                                    <div class="table-control-content">
                                        <button class="btn btn-remove table-control-remove-button confirm-delete-dialog" type="button">X</button>
                                        {{ Form::model($rec, array('class' => 'accessibility', 'method' => 'DELETE', 'url' => route($routes['destroy'], array_merge($ids, array($rec->id))))) }}
                                        {{ Form::close() }}

                                        <div class="dialog-confirm" style="display: none;">
                                            <p>{{ __('dialog.remove') }}</p>
                                        </div>

                                    </div>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@stop
