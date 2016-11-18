<style>
    .small-input {
        width:20%;
        margin-left: 5%;
    }
    .js-ranged {
        width:70%;
    }
</style>
<label class="form-group js-placeholder" for="{{ $name }}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
        	<span class="form-control">
    	        {!! Form::input("range", $name.'_range', ($value ?: $min), ['min' => $min, 'max' => $max, 'step' => $step, 'class' => 'js-ranged form-field-content' . ($required ?' js-required':''), 'id' => $name]) !!}
                {!! Form::input('number', $name, ($value ?: $min), ['min' => $min, 'max' => $max, 'step' => $step, 'class' => 'small-input form-field-content' . ($required ?' js-required':''), 'id' => $name]) !!}
    	        <span class="form-group-highlight"></span>
    	    </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>

