<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }} 
            
        </span>
        <span class="form-field">
        	<span class="form-control">
                @if(isset($flags['default_value']) && substr(URL::current(), -6) == "create")
    	           {{ Form::input($type, $name, (isset($translation_value)) ? $translation_value : $flags['default_value'], array('class' => 'form-field-content' . ($required ?' js-required':''), 'id' => $name)) }}
                @else
                    {{ Form::input($type, $name, (isset($translation_value)) ? $translation_value : null, array('class' => 'form-field-content' . ($required ?' js-required':''), 'id' => $name)) }}
                @endif
    	        <span class="form-group-highlight"></span>
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
