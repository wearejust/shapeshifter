<label class="form-group">
    <span class="form-label">
        {{ $label }}
    </span>
	<span class="form-field">
        <span class="form-control form-field-medium">
            <span class="module-1">
                {{ Form::select($name, $values, null, array('class' => 'form-field-content')) }}
            </span>
            <span class="form-group-highlight"></span>
        </span>
    	@include('shapeshifter::layouts.helptext')
    </span>
</label>
