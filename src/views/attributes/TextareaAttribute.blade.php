<label class="form-group">
    <span class="form-label">
        {{$name}}
    </span>
    <span class="form-field">
    	<span class="form-control">
	        {{ Form::textarea($name, null, array('class' => 'form-field-content')) }}
	        <span class="form-group-highlight"></span>
	    </span>
	    @include('shapeshifter::layouts.helptext')
    </span>
</label>