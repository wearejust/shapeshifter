<label class="form-group form-group-ckeditor js-placeholder" for="{{ $name }}">
    <span class="form-group-content">
	    <span class="form-label">
	        {!! Form::label($name, $label)  !!}
	    </span>
	    <span class="form-field">
	    	<span class="form-control">
		        {!! Form::textarea($name, null, array('class' => 'input-block-level ckeditor' . ($required?' js-required':''), 'id' => $name)) !!}
		    </span>
		    @include('shapeshifter::layouts.helptext')
	    </span>
	</span>
</label>
