<fieldset class="section section-sub">
    <legend class="wrap">
        @if ( isset($translation_value) )
    	    <span class="" style="display: block; font-weight: bold; margin-bottom: 1.375rem;">{{ last_block_value($name) }}</span>
    	@else
    	    <span class="" style="display: block; font-weight: bold; margin-bottom: 1.375rem;">{{ $name }}</span>
        @endif
    </legend>