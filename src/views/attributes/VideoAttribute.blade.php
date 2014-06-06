<label class="form-group">
    <span class="form-label">
        {{ Form::label($name, $label) }}
    </span>
    <span class="form-field embedded-video">
        <span class="form-control">
            {{  Form::text($name, null, array('class' => 'form-field-content embedded-video-input')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>