<div class="form-group">
    <div class="form-label">
        {{ Form::label($name, $label) }}
    </div>
    <div class="form-field">
    	<div class="form-control">
    		<div class="" style="width: 50%;">
        		{{ Form::text($name, null, array('class' => 'datetimepicker')) }}
        	</div>
        </div>
        @include('shapeshifter::layouts.helptext')
    </div>
</div>