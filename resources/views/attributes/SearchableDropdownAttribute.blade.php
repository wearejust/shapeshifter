<label class="form-group" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label" style="float: left; margin: 0;">
            {{ $label }}
        </span>
    	<span class="form-field" style="float: right;">
            <span class="form-control form-field-medium">
                <span class="module-1" style="margin-right: 0;">
                    {!! Form::select($name, $values, null, array('class' => 'form-field-content js-select2' . ($required?' js-required':''), 'id' => $name)) !!}
                </span>
            </span>
        	@include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>


@section('scripts')
    @parent
    <script src="{{ asset('packages/just/shapeshifter/js/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>
@stop

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('packages/just/shapeshifter/js/select2/css/select2.min.css') }}">
@stop
