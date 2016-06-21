<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            @if ($model->{$name} instanceof Carbon\Carbon)
                {{ $model->{$name}->formatLocalized('%A %d %B %Y %H:%M') }}
            @else
                {{ $model->{$name} }}
            @endif
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
