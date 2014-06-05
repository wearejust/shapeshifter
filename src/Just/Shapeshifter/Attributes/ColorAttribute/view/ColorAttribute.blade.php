<label class="form-group">
    <span class="form-label">
        {{$label}}
    </span>
    <span class="form-field">
        <span class="form-control">
            {{ Form::text($name, null, array('class' => 'form-field-content colorpicker')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>