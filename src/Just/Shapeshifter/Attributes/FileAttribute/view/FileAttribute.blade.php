<div class="form-group">
    <div class="form-label">
        {{ Form::label($name, $label) }}
    </div>
    <div class="form-field js-image-container">
        <div class="form-control" style="display: table; table-layout: fixed; width: 100%;">
            <div class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <div class="media-wrapper" style="margin: 0 3px 0 0;">
                    <div class="media-wrapper-content">
                        <div class="media-wrapper-content-wrapper">
                            <div class="media-wrapper-content-wrapper-inner">
                                {{ Form::file($name) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6" style="display: table-cell; vertical-align: top; width: 50%;">
                <div class="media-wrapper" style="margin: 0 0 0 3px;">
                    @if ($value)
                    <div class="media-wrapper-content">
                        <div class="media-wrapper-content-wrapper">
                            <div class="media-wrapper-content-wrapper-inner">
                                {{ $value }}
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-remove confirm-delete-dialog" data-callback="removeImage" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;">X</a>
                    @endif
                </div>
            </div>
        </div>
        {{--
        <div class="group paragraph section-end">
            <div class="col-6">
                {{ Form::select($name . '_existing', $relatives) }}
            </div>
        </div>
        --}}
        @include('shapeshifter::layouts.helptext')
    </div>
</div>