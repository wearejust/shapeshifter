<label class="form-group">
    <span class="form-label">
        {{ $label }}
    </span>
    <span class="form-field">
    	<span class="form-control form-field-short">
    		{{ Form::text($name, null, array('class' => 'form-field-content datepicker', 'pattern' => '(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))-(?:(?:0[1-9]|1[0-2])-(?:19|20)[0-9]{2}', 'placeholder' => 'dd-mm-jjjj', 'autocorrect' => 'off')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>