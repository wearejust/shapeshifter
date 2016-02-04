<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<label class="form-group js-placeholder js-latlngattribute" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
        	<span class="form-control">
                {{ Form::input('text', $name . '-search', null, array('class' => 'js-latlngattribute-search form-field-content', 'placeholder' => 'Search')) }}
                <div class="form-field-content" style="padding-bottom: 56.25%;">
                    <div class="js-latlngattribute-map" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;"></div>
                </div>
                @if (!in_array('compact', $flags))
                    {{ Form::input('text', $name . '-lat', null, array('class' => 'js-latlngattribute-lat form-field-content', 'placeholder' => 'Latitude')) }}
                    {{ Form::input('text', $name . '-lon', null, array('class' => 'js-latlngattribute-lng form-field-content', 'placeholder' => 'Longitude')) }}
                @endif
                {{ Form::hidden($name, (isset($translation_value)) ? $translation_value : (isset($flags['default_value']) ? $flags['default_value'] : null), array('class' => 'js-latlngattribute-input form-field-content' . ($required ?' js-required':''), 'id' => $name)) }}
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>