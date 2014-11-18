<label class="form-group">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
            <span class="form-control form-field-medium">
                {{ Form::select($name, $select, null, array('class' => 'form-field-content')) }}
                <span class="form-group-highlight"></span>
            </span>
            @include('shapeshifter::layouts.helptext')
        </span>
    </span>
</label>
