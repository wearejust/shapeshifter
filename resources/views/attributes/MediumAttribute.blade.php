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

        <script>
            function mediumAttributeOptions() {
                return {
                    'anchor': {
                        'customClassOption': null,
                        'customClassOptionText': 'Button',
                        'targetCheckbox': true
                    },
                    'buttonLabels': 'fontawesome',
                    'disableExtraSpaces': true,
                    'extensions': {
//                        'blockquote_small': new MediumButton({
//                            'label': '<i class="fa fa-quote-right">&nbsp;&nbsp;<sub>Klein</sub></i>',
//                            'tag': 'blockquote',
//                            'class': 'small'
//                        }),
//                        'blockquote_large': new MediumButton({
//                            'label': '<i class="fa fa-quote-right">&nbsp;&nbsp;<sub>Groot</sub></i>',
//                            'tag': 'blockquote',
//                            'class': 'big'
//                        }),
//                        'textexpander': new TextExpander()
                    },
                    'imageDragging': false,
                    'paste': {
                        'forcePlainText': true,
                        'cleanPastedHTML': true,
                        'cleanReplacements': [],
                        'cleanAttrs': ['class', 'style', 'dir'],
                        'cleanTags': ['meta'],
                        'unwrapTags': []
                    },
                    'toolbar': {
                        'allowMultiParagraphSelection': true,
                        'buttons': ['bold', 'italic', 'anchor', 'h2', 'h3', 'orderedlist', 'unorderedlist', 'quote'],
                        'sticky': true,
                        'updateOnEmptySelection': true,
                        'diffLeft': 0,
                        'diffTop': -5
                    }
                };
            }

            function mediumInsertOptions() {
                return {
                     'addons': {
                         'embeds': {
                             'label': '<span class="fa fa-code"></span>',
                             'captionPlaceholder': '{{ trans('shapeshifter::editor.captionPlaceholder') }}',
                             'placeholder': '{{ trans('shapeshifter::editor.embedPlaceholder') }}',
                             'oembedProxy': '{{ config('shapeshifter.oembed_proxy') }}',
                             'styles': null
                         },
                         'filebrowser': true,
                         'images': false
                     }
                };
            }

        </script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.runtime.min.js"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/medium/rangy-core.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/medium/rangy-classapplier.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/medium/medium-editor.min.js') }}"></script>
{{--        <script src="{{ asset('packages/just/shapeshifter/js/medium/extensions/textexpander.js') }}"></script>--}}
        <script src="{{ asset('packages/just/shapeshifter/js/medium/medium-insert/medium-editor-insert-plugin.min.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/medium/medium-insert/addons/filebrowser.js') }}"></script>
        <script src="{{ asset('packages/just/shapeshifter/js/medium/medium.js') }}"></script>

        <?php $_SERVER['SCRIPTS_LOADED_MEDIUM'] = true; ?>
    @endif

@stop

@section('styles')
    @parent
    @if (!isset($_SERVER['STYLES_LOADED_MEDIUM']))
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/css/medium/medium-editor.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/css/medium/themes/default.min.css') }}">
{{--        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/css/medium/extensions/textexpander.css') }}">--}}
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/css/medium/medium-insert/medium-editor-insert-plugin.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/css/medium/medium.css') }}">
        <?php $_SERVER['STYLES_LOADED_MEDIUM'] = true; ?>
    @endif
@stop
