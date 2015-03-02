<label class="form-group" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label" style="float: left; margin: 0;">
            {{ $label }}
        </span>
    	<span class="form-field" style="float: right;">
            <span class="form-control form-field-medium">
                <span class="module-1" style="margin-right: 0;">
                     {{ Form::select($name, $values, null, array('style'=> "width: 80px;", 'class' => 'form-field-content' . ($required?' js-required':''), 'id' => $name)) }}
                    {{ Form::select($name."_min", $values2, $value2, array('style'=> "width: 80px;", 'class' => 'form-field-content' . ($required?' js-required':''), 'id' => $name."_min")) }}
                    <span class="form-group-highlight"></span>
                </span>
            </span>
        	@include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>