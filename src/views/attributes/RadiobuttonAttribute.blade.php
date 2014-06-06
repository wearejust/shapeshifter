<div class="form-group">
	<fieldset>
		<legend class="form-label wrap section-end">
			<span class="block form-label">{{ $label }}</span>
		</legend>
		<div class="form-field radio">
			<div class="form-options">
				@foreach ($values as $value)
		        <div class="section form-option">
			        {{ Form::radio($name, $value, null, array('class' => 'form-option-field', 'id' => 'radio-' . $value)) }}
			        <label class="form-option-label" for="radio-{{ $value }}">
			            {{ $value }}
			        </label>
			    </div>
				@endforeach
			</div>
			@include('shapeshifter::layouts.helptext')
	    </div>
	</fieldset>
</div>