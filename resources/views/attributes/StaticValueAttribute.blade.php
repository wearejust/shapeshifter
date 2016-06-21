<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            {!! $value !!}
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
