<div class="form-group form-group-options">
	<fieldset>
		<legend class="form-label wrap section-end">
			<span class="form-label form-legend">{{ $label }}</span>
		</legend>
		<div class="form-field radio">
			<div class="form-options">
				<ul class="list form-options-content">
					@foreach ($values as $value)
			        <li class="form-option form-option-grid-2 form-option-grid-3"> <!-- grid-2 gebruiken voor 2 items naast elkaar, grid-3 voor 3 (op groot scherm) -->
				        <div class="form-option-content">
					        {{ Form::radio($name, $value, null, array('class' => 'form-option-field' . ($required?' js-required':''), 'id' => 'radio-' . $value)) }}
					        <label class="form-option-label" for="radio-{{ $value }}">
					            <span class="section">{{ $value }}</span>
					        </label>
					     </div>
				    <!--</li>-->
					@endforeach
				</ul>
			</div>
			@include('shapeshifter::layouts.helptext')
	    </div>
	</fieldset>
</div>