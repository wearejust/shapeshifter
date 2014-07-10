<div class="form-group form-group-options">
	<fieldset>
		<legend class="form-label wrap section-end">
			<span class="form-label form-legend">{{ $label }}</span>
		</legend>
		<div class="form-field radio">
			<div class="form-options">
				@foreach ($values as $value)
		        <div class="section form-option">
			        {{ Form::radio($name, $value, null, array('class' => 'form-option-field' . ($required?' js-required':''), 'id' => 'radio-' . $value)) }}
			        <label class="form-option-label" for="radio-{{ $value }}">
			            <span class="section">{{ $value }}</span>
			        </label>
			    </div>
				@endforeach
			</div>
			@include('shapeshifter::layouts.helptext')
	    </div>
	</fieldset>
</div>