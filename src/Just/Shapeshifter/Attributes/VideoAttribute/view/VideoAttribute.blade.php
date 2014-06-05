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

        <!-- met js toevoegen -->
        <span class="block container">
            <span class="hide video-preview-loader paragraph"></span>
            <span class="hide section section-end paragraph video video-preview">
                <iframe src="" width="522" height="380" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </span>
        </span>

    </span>
</label>