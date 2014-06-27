<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
	    <span class="form-label">
	        {{ Form::label($name, $label) }}
	    </span>
	    <span class="form-field">
	    	<span class="form-control">
		        {{  Form::textarea($name, null, array('class' => 'input-block-level ckeditor', 'id' => $name)) }}
		    </span>
		    @include('shapeshifter::layouts.helptext')
	    </span>
	</span>
</label>