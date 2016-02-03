<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<label class="form-group js-placeholder js-latlngattribute" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
        	<span class="form-control">
                <div class="form-map" style="padding-bottom: 56.25%;"></div>
                {{ Form::input('text', $name, (isset($translation_value)) ? $translation_value : (isset($flags['default_value']) ? $flags['default_value'] : null), array('class' => 'form-field-content' . ($required ?' js-required':''), 'id' => $name)) }}
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>