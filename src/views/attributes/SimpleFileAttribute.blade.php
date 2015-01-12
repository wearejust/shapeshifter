<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field js-image-container">
            <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
                <span class="" style="display: table-cell; vertical-align: top; width: 100%;">
                  {{ Form::file($name)}} {{$value}}
                </span>
            </span>

            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
