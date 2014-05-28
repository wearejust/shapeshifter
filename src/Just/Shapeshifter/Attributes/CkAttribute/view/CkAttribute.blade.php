<div class="form-group">
    <div class="form-label">
        {{ Form::label($name, $label) }}
    </div>
    <div class="form-field">
    	<div class="form-control">
	        {{  Form::textarea($name, null, array('class' => 'input-block-level ckeditor')) }}
	    </div>
	    @include('shapeshifter::layouts.helptext')
    </div>
</div>