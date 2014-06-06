<label class="form-group">
    <span class="form-label">
        {{ $labe l}}
    </span>
    <span class="form-field">
        <span class="form-control form-field-medium">
            {{ Form::select($name, $select, null, array('class' => 'form-field-content')) }}
            <span class="form-group-highlight"></span>
        </span>
        @include('shapeshifter::layouts.helptext')
    </span>
</label>
