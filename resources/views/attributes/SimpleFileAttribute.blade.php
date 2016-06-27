<label class="form-group js-placeholder js-simplefileattribute" for="{{ $name }}" data-max-width="{{ $maxWidth }}" data-max-height="{{ $maxHeight }}" data-max-size="{{ $maxSize }}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
            @include('shapeshifter::layouts.filesizes')
        </span>
        <span class="form-field js-image-container">
            <span class="form-control">
                <span class="block">{!! Form::file($name) !!}</span>
                @if($model->{$name})
                    <span class="block" style="padding: 1em;">
                        <strong>{{ trans('shapeshifter::site.form.current') }}:</strong> {!! $model->{$name} !!}
                        @if($type == 'image')
                            <img class="block" src="{{ $relativeStorageDir . '/' . $model->{$name} }}">
                        @endif
                    </span>
                @endif
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
