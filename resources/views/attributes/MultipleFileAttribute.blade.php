<label class="form-group js-multiplefileattribute" data-storage-dir="{{ $relativeStorageDir }}" data-max-width="{{ $maxWidth }}" data-max-height="{{ $maxHeight }}" data-max-size="{{ $maxSize }}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
            @include('shapeshifter::layouts.filesizes')
        </span>
        <span class="form-field js-image-container">
            <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
                <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                    <span class="media-wrapper module-1">
                        <span class="media-wrapper-content">
                            <span class="media-wrapper-content-wrapper media-preview">
                                <span class="media-wrapper-content-wrapper-inner js-multiplefileattribute-preview" style="background-image: url('{{ $model->{$name} ? ($relativeStorageDir . $model->{$name}) : '' }}');"></span>
                                <button class="btn btn-remove btn-remove-alt confirm-delete-dialog {{ $model->{$name} ? '' : 'hide' }}" data-callback="removeImage" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;" type="button">X</button>
                                <span class="media-placeholder js-multiplefileattribute-preview-placeholder {{ $model->{$name} ? 'hide' : '' }}">
                                    <span class="media-placeholder-content">
                                        <p class="section-start section-end js-multiplefileattribute-preview-placeholder">Kies een eerder geüploade afbeelding of gebruik de "+"-knop om afbeeldingen te uploaden.</p>
                                    </span>
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
                <span class="" style="position: relative; display: table-cell; vertical-align: top; width: 50%;">
                    <span class="mini-gallery module-2">
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
                                <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}" title="{{ $key }}">
                                    <span class="mini-gallery-list-item-content">
                                        <span class="mini-gallery-list-item-content-inner">
                                            {!! Form::radio($name, $key, null, array('class' => 'form-option-field mini-gallery-input accessibility' . ($required?' js-required':''), 'id' => 'radio-' . $name . '-'. $key)) !!}
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
    </span>
</label>
