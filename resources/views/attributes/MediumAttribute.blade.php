<label class="form-group" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>

{!! Form::textarea($name, null, ['class' => 'medium-editable', 'id' => $name, 'data-placeholder' => trans('shapeshifter::editor.placeholder'), 'data-dir' => url(config('elfinder.dir')[0]).'/', 'data-elfinder-url' => route('elfinder.popup', 'editor')])  !!}
<br>
<br>
@section('scripts')
    @parent

    @if (!isset($_SERVER['SCRIPTS_LOADED_MEDIUM']))
        <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/jquery.ui.widget.js') }} "></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.iframe-transport.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

        <script>
            var mediumAttribute = {
                'translations': {
                    captionPlaceholder: "{{ trans('shapeshifter::editor.captionPlaceholder') }}",
                    embedPlaceholder: "{{ trans('shapeshifter::editor.embedPlaceholder') }}"
                }
            };
        </script>

        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/js/medium-editor.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium-editor-insert-plugin/dist/js/medium-editor-insert-plugin.min.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/js/filebrowser.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/medium-button.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/extensions/pastehandler.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium.js') }}"></script>
        <?php $_SERVER['SCRIPTS_LOADED_MEDIUM'] = true; ?>
    @endif

@stop

@section('styles')
    @parent
    @if (!isset($_SERVER['STYLES_LOADED_MEDIUM']))
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/css/medium-editor.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/css/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.css') }}">
        <?php $_SERVER['STYLES_LOADED_MEDIUM'] = true; ?>
    @endif
@stop
