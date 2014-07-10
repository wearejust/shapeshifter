<label class="form-group">
	<span class="form-group-content">
	    <span class="form-label form-label-checkbox">
	        {{ $label }}
	    </span>
	    <span class="form-field form-field-checkbox">
			{{ Form::checkbox($name, 1, Input::get($name) ?: null, array('class' => 'form-single-checkbox switch' . ($required?' js-required':''), 'id' => $name)) }}
			<span class="switch"></span>
	        <span class="form-group-highlight"></span>
		    @include('shapeshifter::layouts.helptext')
	    </span>
	</span>
</label>