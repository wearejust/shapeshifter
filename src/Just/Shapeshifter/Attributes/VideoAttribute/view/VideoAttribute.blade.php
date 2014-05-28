<div class="form-group">
    <div class="form-label">
        {{ Form::label($name, $label) }}
    </div>
    <div class="form-field embedded-video">
        <div class="container">
            <div class="form-control">
                {{  Form::text($name, null, array('class' => 'embedded-video-input')) }}
            </div>
            @include('shapeshifter::layouts.helptext')
        </div>
        <span class="hide video-preview-loader paragraph"></span>
        <div class="hide section section-end paragraph video video-preview">
            <iframe src="" width="522" height="380" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
        </div>
    </div>
</div>