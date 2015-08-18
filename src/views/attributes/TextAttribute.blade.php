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
                    {{ Form::input($type, $name, (isset($translation_value)) ? $translation_value : null, array('class' => 'form-field-content' . ($required ?' js-required':'') . (array_key_exists('max_length', $flags) ?' max-length':''), 'id' => $name, 'maxlength'=>(array_key_exists('max_length', $flags) ?$flags['max_length']:''))) }}
                @endif


                @if(array_key_exists('max_length', $flags)) 

                   <sub class='character-counter' id='{{$name}}-counter' style='line-height: 15px;'>{{$flags['max_length'] - strlen($translation_value)}}</sub>                

                @endif


    	        <span class="form-group-highlight"></span>
    	    </span>
    	    @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
