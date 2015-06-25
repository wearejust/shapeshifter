@extends('shapeshifter::layouts.master')

@section('content')
<div class="content-body-inner">
    <h1>{{ $title }}</h1>
    
    @if ($currentUser->can('create') || ((count($records) || $paginate) && $currentUser->can('sort')))
    <div class="group">
        @if ($currentUser->can('create'))
        <div class="add-item">

            @if (isset($addItems) )
               @foreach ($addItems as $item)
                   <a href="{{ $item['url'] }}" class="btn btn-default add-item-button" style="margin:0;">{{ $item['title'] }}</a>
               @endforeach
            @else
              <a class="btn btn-default add-item-button" href="{{ route($routes['create'], $ids) }}" id="add-item">{{ __('form.create') }}</a>
            @endif
        </div>
        @endif
        @if ((count($records) || $paginate) && $currentUser->can('sort'))
            @if(!in_array('search', $disabledActions))
                <div class="filter-search">
                    <form action="" class="section-start section-end{{ $paginate ? ' search-pagination' : '' }}" method="get">
                        <fieldset>
                            <label class="accessibility" for="search">{{ __('form.search') }}</label>
                            <input class="search-control" name="search" id="search" placeholder="{{ __('form.search') }}…" type="search" value="{{ Input::get('search') }}">
                             <input name="count" type="hidden" value="{{ $paginate_count }}">
                            <button class="search-button" type="submit">{{ __('form.search') }}</button>
                        </fieldset>
                    </form>
                </div>
            @endif
        @endif
    </div>
    @endif

    <div class="section paragraph" id="datatable">
        @if (!count($records))
        <div class="section paragraph">
            <div class="form-group">
                <p class="form-group-content">{{ __('form.no-records') }}</p>
            </div>
        </div>
        @else
        <div class="content-alt">
            <div class="data-wrapper">
                <table class="section section-sub section-start js-datatable {{ $currentUser->can('drag') ? 'js-datatable-order' : '' }} {{ $currentUser->can('sort') ? 'js-datatable-sortable' : ''}}" data-sort-column="{{ $orderBy[0] }}" data-sort-order="{{ $orderBy[1] }}" data-sort-offset="{{ $sort_offset }}">
                    <thead>
                        @foreach ($attributes as $attr)
                        @if ( ! $attr->hasFlag('hide_list'))
                        <th class="table-header {{ $currentUser->can('drag') && ! $currentUser->can('sort') ? 'js-disable-sort' : '' }} {{ ! $currentUser->can('drag') && $lastVisibleAttribute == $attr ? 'table-header-last' : '' }}{{ ($sort == $attr->name) ? (' table-header-sort-item-active-' . $sortdir) : '' }}" data-header-title="{{ $attr->name }}">
                            <div class="container">
                                @if ((!$paginate || $attr->sortable) && ! $currentUser->can('drag') && $currentUser->can('sort'))
                                    <span class="table-header-sort">
                                        <a {{ ($paginate && $attr->sortable) ? "{$attr->sortable}asc\"" : '' }} class="table-header-sort-item table-header-sort-item-asc"><span class="accessibility">Oplopend</span></a>
                                        <a {{ ($paginate && $attr->sortable) ? "{$attr->sortable}desc\""  : '' }} class="table-header-sort-item table-header-sort-item-desc"><span class="accessibility">Aflopend</span></a>
                                    </span>
                                @endif
                                {{ translateAttribute($attr->name) }}
                            </div>
                        </th>
                        @endif
                        @endforeach
                        @if ($currentUser->can('drag') && count($records) > 1)
                        <th class="table-header table-order js-disable-sort table-header-last"></th>
                        @endif
                        @if ($currentUser->can('delete'))
                        <th class="table-header table-control js-disable-sort"></th>
                        @endif
                    </thead>
                    <tbody>
                        @foreach ($records as $rec)
                        <tr id="itemRow_{{ $rec->id }}" class="table-row js-transform {{ ! in_array($rec->id, $disableEditing) ? 'table-row-editable' : '' }} {{ ! in_array($rec->id, $disableDeleting) ? 'table-row-deletable' : '' }}" data-edit-href="{{ route($routes['edit'], array_merge($ids, array($rec->id))) }}" data-record-id="{{ $rec->id }}">
                            @foreach ($attributes as $attr)
                                @if ( ! $attr->hasFlag('hide_list'))
                                    <td class="table-cell {{ ! $currentUser->can('drag') && $lastVisibleAttribute == $attr ? 'table-cell-last' : '' }}">{{ $rec->{$attr->name} }}</td>
                                @endif
                            @endforeach
                            @if ($currentUser->can('drag') && count($records) > 1)
                            <td class="table-cell table-order table-cell-last">
                                <button class="js-sortable-handle tricon link-alt item-alt" type="button"><span class="accessibility">Verplaatsen</span></button>
                            </td>
                            @endif
                            @if ($currentUser->can('delete'))
                            <td class="table-cell table-control">
                                <div class="container">
                                    @if ( ! in_array($rec->id, $disableDeleting) )
                                    <div class="table-control-content media-wrapper js-remove-wrapper">
                                        <button class="btn btn-remove table-control-remove-button confirm-delete-dialog" data-id="{{ $rec->id }}" type="button">X</button>
                                        {{ Form::model($rec, array('class' => 'accessibility', 'id' => 'deleteItem_'.$rec->id, 'method' => 'DELETE', 'url' => route($routes['destroy'], array_merge($ids, array($rec->id))))) }}
                                        {{ Form::close() }}
                                        <div class="dialog-confirm" style="display: none;">
                                            <p>{{ __('dialog.remove') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($paginate)
        <div class="pagination-wrap group">
            <select class="pagination-count">
                @foreach ($paginate_counts as $count)
                  <option value="{{ strtolower($count) }}"{{ $paginate_count==strtolower($count)?' selected':''}}>{{ $count }}</option>
                @endforeach
            </select>
            @if ($paginate)
                {{ $records->appends(array('search' => Input::get('search'), 'sort' => Input::get('sort'), 'sortdir' => Input::get('sortdir'), 'count'=>$paginate_count))->links() }}
            @endif
        </div>
        @endif
        @endif
    </div>
</div>
<div class="footer controls">
    @if ( Notification::get('success')->first())
        {{ Notification::showSuccess() }}
    @endif
</div>
@stop