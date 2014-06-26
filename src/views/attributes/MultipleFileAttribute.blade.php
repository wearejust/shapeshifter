<label class="form-group js-multiplefileattribute">
    <span class="form-label">
        {{ $label }}
    </span>
    <span class="form-field js-image-container">
        <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
            <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <span class="media-wrapper" style="margin: 0 3px 0 0;">
                    <span class="media-wrapper-content">
                        <span class="media-wrapper-content-wrapper">
                            <span class="media-wrapper-content-wrapper-inner">
                                
                                {{--
                                <button class="btn btn-remove confirm-delete-dialog" data-callback="removeImage" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;" type="button">X</button>
                                --}}

                                <img class="wrapper{{ !$value ? ' hide' : ''}}" alt="" src="{{ $value ? $relativeStorageDir . $value : '' }}"  data-storage-dir="{{ $relativeStorageDir }}">

                                @if (!$value)
                                  <p>Kies een eerder geüploade afbeelding of gebruik de "+"-knop om (meer) afbeeldingen te uploaden.</p>
                                @endif
                            </span>
                        </span>
                    </span>
                </span>
            </span>
            <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <span class="mini-gallery" style="display: block; margin: 0 0 0 3px;">
                    <fieldset>
                        <ul class="mini-gallery-list">
                            <li class="mini-gallery-list-item hide">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <span class="mini-gallery-add fill">+</span>
                                        <input accept="image/*" class="mini-gallery-add-button fill" name="files[]" type="file" multiple>
                                    </span>
                                </span>
                            <!--</li>-->

                            @foreach ($existing as $key => $image)
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        {{ Form::radio($name, $key, null, array('class' => 'form-option-field mini-gallery-input accessibility', 'id' => 'radio-' . $name . '-'. $key)) }}
                                        <label class="mini-gallery-thumb-button fill" for="radio-{{ $name }}-{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                                <!--</li>-->
                            @endforeach
                        </ul>
                    </fieldset>
                </span>
            </span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>
