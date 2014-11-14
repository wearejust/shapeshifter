<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            @if ($value instanceof Carbon\Carbon)
                {{ $value->formatLocalized('%A %d %B %Y %H:%M') }}
            @else
                {{ $value }}
            @endif
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
