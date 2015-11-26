<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">

        <span class="form-label">
            {{ $label }}
        </span>

        <span class="form-field">
        	<span class="form-control">

                {{ Form::input($type, $name, $attr->getEditValue(), array('class' => 'form-field-content' . ($required ?' js-required':'') . (array_key_exists('max_length', $flags) ?' max-length':''), 'id' => $name, 'maxlength'=>(array_key_exists('max_length', $flags) ?$flags['max_length']:''))) }}

                @if(array_key_exists('max_length', $flags))
                   <sub class='character-counter' id='{{ $name }}-counter' style='line-height: 15px;'>{{ $flags['max_length'] - strlen($translation_value) }} characters left</sub>
                @endif

    	        <span class="form-group-highlight"></span>
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
