<label class="form-group">
    <span class="form-label">
        {{$label}}
    </span>
    <span class="form-field">
    	<span class="form-control form-field-short">
    		{{ Form::text($name, null, array('class' => 'form-field-content datepicker', 'placeholder' => 'dd-mm-jjjj')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>