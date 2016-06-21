<label class="form-group js-placeholder" for="{{ $name }}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field js-image-container">
            <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
                @if($model->{$name})
                    <span style="display: table-cell; vertical-align: middle; width: 100%;">
                      <strong>Huidig:</strong> {!! $model->{$name} !!}
                    </span>
                @endif
                <span class="" style="display: table-cell; vertical-align: top; width: 100%;">
                  {!! Form::file($name) !!}
                </span>
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
