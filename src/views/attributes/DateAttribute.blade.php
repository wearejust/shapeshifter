<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            <span class="form-control form-field-short">
                <span class="module-1">
                    {{ Form::text($name, null, array('class' => 'form-field-contsdaf aswdfsdaent datepicker' . ($required?' js-required':''), 'id' => $name, 'pattern' => '(0[1-9]|1[0-9]|2[0-9]|3[0-1])-(0[1-9]|1[0-2])-([0-9]{4})', /*'placeholder' => 'dd-mm-jjjj', */'autocorrect' => 'off')) }}
                </span>
                <span class="form-group-highlight"></span>
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>