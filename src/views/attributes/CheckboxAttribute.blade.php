<div class="form-group">
	<div class="form-field checkbox">
		<div class="group">
	        {{ Form::checkbox($name, 1, Input::get($name) ?: null, array('class' => 'single-checkbox', 'id' => $name)) }}
	        {{ Form::label($name, $label, array('class' => 'section-start section-end single-checkbox-label', 'for' => $name)) }}
        </div>
        @include('shapeshifter::layouts.helptext')
    </div>
</div>