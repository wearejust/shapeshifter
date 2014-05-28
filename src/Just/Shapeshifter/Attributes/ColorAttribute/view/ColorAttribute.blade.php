<div class="form-group">
    <div class="form-label col-3">
        {{ Form::label($name, $label) }}
    </div>
    <div class="form-field col-9">
        <div class="form-control">
            {{ Form::text($name, null, array('class' => 'colorpicker')) }}
        </div>
        @include('shapeshifter::layouts.helptext')
    </div>
</div>

