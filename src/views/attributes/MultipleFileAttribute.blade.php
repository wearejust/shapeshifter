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
                                <img alt="" src="">
                                <button class="btn btn-remove confirm-delete-dialog" data-callback="removeImage" data-name="{{ $name }}" style="height: 2.75em; line-height: 2.75em; padding: 0; position: absolute; right: 0; top: 0; width: 2.75em;" type="button">X</button>
                                --}}

                                <p>Kies een eerder geüploade afbeelding of gebruiken de "+"-knop om een of meerdere afbeeldingen te uploaden.</p>

                            </span>
                        </span>
                    </span>
                </span>
            </span>
            <span class="" style="display: table-cell; vertical-align: top; width: 50%;">
                <span class="mini-gallery" style="display: block; margin: 0 0 0 3px;">
                    <ul class="mini-gallery-list">
                        <li class="mini-gallery-list-item">
                            <span class="mini-gallery-list-item-content">
                                <span class="mini-gallery-list-item-content-inner">
                                    <span class="mini-gallery-add fill">+</span>
                                    <input accept="image/*" class="mini-gallery-add-button" id="" type="file" multiple>
                                </span>
                            </span>
                        <!--</li>-->
                        <li class="mini-gallery-list-item">
                            <span class="mini-gallery-list-item-content">
                                <span class="mini-gallery-list-item-content-inner">
                                    <button class="mini-gallery-thumb-button loader fill" type="button"><img alt="" class="mini-gallery-thumb fill" src=""></button>
                                </span>
                            </span>
                        <!--</li>-->
                        <li class="mini-gallery-list-item">
                            <span class="mini-gallery-list-item-content">
                                <span class="mini-gallery-list-item-content-inner">
                                    <button class="mini-gallery-thumb-button loader mini-gallery-thumb-button-selected fill" type="button"><img alt="" class="mini-gallery-thumb fill" src=""></button>
                                </span>
                            </span>
                        <!--</li>-->
                    </ul>
                </span>
            </span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>