<label class="form-group">
    <span class="form-label">
        {{$label}}
    </span>
    <span class="form-field">
        <span class="form-control">
            <span class="" style="display: block; width: 50%;">
                {{ Form::select($name, $select, null, array('class' => 'form-field-content')) }}
                <span class="form-group-highlight"></span>
            </span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>