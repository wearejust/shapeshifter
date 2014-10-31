<div class="section form-group form-group-options">
	<div><!-- style="transform: translateX(-100%); transition: transform .4s;">-->
		<div class="form-group-content form-group-options-content">
			<fieldset class="section-start section-end">
				<legend class="form-label wrap section-end">
					<span class="form-legend">{{ $label }}</span>
				</legend>
				<div class="form-field radio">
					<div class="form-options">
						<ul class="list form-options-content">
							@foreach ($values as $value)
					        <li class="form-option"> {{-- form-option-grid-2 gebruiken voor 2 items naast elkaar, form-option-grid-3 voor 3 (op groot scherm); form-option-dependency voor afhankelijkheden --}}
						        {{ Form::radio($name, $value, null, array('class' => 'form-option-field' . ($required?' js-required':''), 'id' => 'radio-' . $value)) }}
						        <label class="form-option-label" for="radio-{{ $value }}">{{ $value }}</label>
						    <!--</li>-->
							@endforeach
						</ul>
					</div>
					@include('shapeshifter::layouts.helptext')
			    </div>
			</fieldset>
		</div>
		{{--
		<div class="form-group-content form-group-options-content" style="box-sizing: border-box; height: 100%; left: 100%; position: absolute; top: 0; width: 100%;">
			<a class="btn btn-default" href="" style="left: 0; position: absolute; top: 0;">Terug</a>
			<button type="button" style="border: none; height: 2.75rem; padding: 0; position: absolute; right: 0; text-indent: -9999em; top: 0; width: 2.75rem;">Sluiten</button>
		</div>
		--}}
	</div>
</div>