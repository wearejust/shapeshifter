<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field js-image-container">
            <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
                <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                    <span class="media-wrapper module-1">
                        <span class="media-wrapper-content">
                            <span class="media-wrapper-content-wrapper">
                                <span class="media-wrapper-content-wrapper-inner">
                                    <span class="media-wrapper-content-wrapper-inner-content">
                                        {{ Form::file($name, array('accept' => '', 'class' => 'form-field-content' . ($required?' js-required':''), 'id' => $name, 'autocorrect' => 'off')) }}
                                        <!--<span class="form-group-highlight"></span>-->
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
                <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                    <span class="media-wrapper module-2">
                        @if ($value)
                        <span class="media-wrapper-content">
                            <span class="media-wrapper-content-wrapper media-preview">
                                <span class="media-wrapper-content-wrapper-inner" style="background-image: url('{{ $value }}');"></span>
                                <button class="btn btn-remove btn-remove-alt js-image-delete-dialog" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;" type="button">X</button>
                            </span>
                        </span>
                        <div class="dialog-confirm" style="display: none;">
                            <p>{{ __('dialog.remove-image') }}</p>
                        </div>
                        @endif
                    </span>
                </span>
            </span>

            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
