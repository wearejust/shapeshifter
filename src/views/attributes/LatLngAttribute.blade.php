<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<label class="form-group js-placeholder js-latlngattribute" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
        	<span class="form-control">
                @if (!in_array('hide_search', $flags))
                    {{ Form::input('text', $name . '-search', null, array('class' => 'js-latlngattribute-search form-field-content', 'placeholder' => __('form.search'))) }}
                @endif
                <div class="form-field-content" style="padding-bottom: 56.25%;">
                    <div class="js-latlngattribute-map" style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;"></div>
                </div>
                @if (in_array('show_fields', $flags))
                    {{ Form::input('number', $name . '-lat', null, array('class' => 'js-latlngattribute-lat form-field-content', 'step' => '0.00000000000001', 'min' => '-90', 'max' => '90', 'placeholder' => 'Latitude')) }}
                    {{ Form::input('number', $name . '-lon', null, array('class' => 'js-latlngattribute-lng form-field-content', 'step' => '0.00000000000001', 'min' => '-180', 'max' => '180', 'placeholder' => 'Longitude')) }}
                @endif
                {{ Form::hidden($name, (isset($translation_value)) ? $translation_value : (isset($flags['default_value']) ? $flags['default_value'] : null), array('class' => 'js-latlngattribute-output form-field-content' . ($required ?' js-required':''), 'id' => $name)) }}
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>