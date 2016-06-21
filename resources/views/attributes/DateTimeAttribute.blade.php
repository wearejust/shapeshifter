<label class="form-group js-placeholder" for="{{ $name }}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
        	<span class="form-control form-field-medium">
                <span class="module-1">
            	   {!! Form::text($name, null, array('class' => 'form-field-content datetimepicker' . ($required?' js-required':''), 'id' => $name, 'autocorrect' => 'off')) !!}
                </span>
                <span class="form-group-highlight"></span>
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>