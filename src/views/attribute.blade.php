<div class="section">
    @foreach ($attributes as $attr)
        @if ( ! Just\Shapeshifter\Services\AttributeService::ignoreAttributes($attr))
        {{ $attr }}
        @endif
    @endforeach
</div>
