<label class="form-group">
    <span class="form-label">
        {{$label}}
    </span>
    <span class="form-field">
    	<span class="form-control" style="width: 50%;">
        	{{ Form::text($name, null, array('class' => 'datetimepicker')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>