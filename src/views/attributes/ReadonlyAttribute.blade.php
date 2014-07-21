<div class="form-group">
    <div class="form-label section-end">
        {{ $label }}
    </div>
    <div class="form-field">
        @if ($value instanceof Carbon\Carbon)
            {{ $value->formatLocalized('%A %d %B %Y %H:%M') }}
        @else
            {{ $value }}
        @endif
	    @include('shapeshifter::layouts.helptext')
    </div>
</div>
