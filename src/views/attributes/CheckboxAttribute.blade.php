<label class="form-group">
    <span class="form-label">
        {{ $label }}
    </span>
    <span class="form-field">
		{{ Form::checkbox($name, 1, Input::get($name) ?: null, array('class' => 'form-single-checkbox switch', 'id' => $name)) }}
		<span class="switch"></span>
        <span class="form-group-highlight"></span>
	    @include('shapeshifter::layouts.helptext')
    </span>
</label>