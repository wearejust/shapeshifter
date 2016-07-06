@if ($maxWidth || $maxHeight || $maxSize)
    <span class="block note special" style="margin-top: 0.125em;">
        Max
        @if ($maxWidth || $maxHeight)
            {{ ($maxWidth ?: '-') . 'x' . ($maxHeight ?: '-') . ($maxSize ? ', ' : '') }}
        @endif
        @if ($maxSize)
            {{ formatBytes($maxSize) }}
        @endif
</span>
@endif