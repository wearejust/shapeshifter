{!! Form::textarea($name, null, ['class' => 'medium-editable', 'id' => $name])  !!}

@section('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/jquery.ui.widget.js') }} "></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.iframe-transport.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.5.7/jquery.fileupload.js"></script>

    <script>
        var translations = {
            captionPlaceholder : "{{ trans('shapeshifter::editor.captionPlaceholder') }}",
            embedPlaceholder   : "{{ trans('shapeshifter::editor.embedPlaceholder') }}",
            iframelyApiKey: ''
        };
    </script>

    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/js/medium-editor.js') }}"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium-editor-insert-plugin/dist/js/medium-editor-insert-plugin.min.js') }}"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/js/filebrowser.js') }}"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/medium-button.js') }}"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/extensions/pastehandler.js') }}"></script>
    <script src="{{ asset('packages/just/shapeshifter/js/attributes/medium/blog.js') }}"></script>

@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/css/medium-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium/css/themes/default.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/attributes/medium/medium-editor-insert-plugin/dist/css/medium-editor-insert-plugin.css') }}">
@stop
