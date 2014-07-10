<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field embedded-video">
            <span class="form-control">
                {{  Form::text($name, null, array('class' => 'form-field-content embedded-video-input' . ($required?' js-required':''), 'id' => $name, 'autocorrect' => 'off')) }}
                <span class="form-group-highlight"></span>
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>