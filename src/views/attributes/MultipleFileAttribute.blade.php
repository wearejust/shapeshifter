<label class="form-group js-multiplefileattribute">
    <span class="form-label">
        {{ $label }}
    </span>
    <span class="form-field js-image-container">
        <span class="form-control" style="display: table; table-layout: fixed; width: 100%;">
            <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <span class="media-wrapper" style="margin: 0 9px 0 0;">
                    <span class="media-wrapper-content">
                        <span class="media-wrapper-content-wrapper">
                            <span class="media-wrapper-content-wrapper-inner">
                                <span class="media-wrapper-content-wrapper-inner-content js-media">
                                    
                                    {{--
                                    <img alt="" src="">
                                    <button class="btn btn-remove confirm-delete-dialog" data-callback="removeImage" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;" type="button">X</button>
                                    --}}

                                    <p>Kies een eerder geüploade afbeelding of gebruiken de <span class="row">"+"-knop</span> om (meer) afbeeldingen te uploaden.</p>
                                </span>
                            </span>
                        </span>
                    </span>
                </span>
            </span>
            <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <span class="mini-gallery" style="display: block; margin: 0 0 0 9px;">
                    <fieldset>
                        <ul class="mini-gallery-list">
                            <li class="mini-gallery-list-item">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <span class="mini-gallery-add fill">+</span>
                                        <input accept="image/*" class="mini-gallery-add-button fill" name="files[]" type="file" data-index="{{ count($existing) }}" data-storage-dir="{{ $relativeStorageDir }}" multiple>
                                    </span>
                                </span>
                            <!--</li>-->

                            @foreach ($existing as $key => $image)
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            @endforeach
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                            <li class="mini-gallery-list-item{{ !$image ? ' hide':'' }}">
                                <span class="mini-gallery-list-item-content">
                                    <span class="mini-gallery-list-item-content-inner">
                                        <input class="mini-gallery-input accessibility" id="option{{ $key }}" name="files" type="radio" value="">
                                        <label class="mini-gallery-thumb-button fill" for="option{{ $key }}">
                                            <img alt="" class="mini-gallery-thumb fill" src="{{ $image }}">
                                        </label>
                                    </span>
                                </span>
                            <!--</li>-->
                        </ul>
                    </fieldset>
                </span>
            </span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>