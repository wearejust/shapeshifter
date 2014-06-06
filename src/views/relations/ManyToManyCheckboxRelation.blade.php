<div class="form-group">
    <fieldset>
        <legend class="form-label wrap section-end">
            <span class="block form-label">{{ $label }}</span>
        </legend>
        <div class="form-field radio">
            <div class="form-options">
                <div class="form-options-content">
                    @foreach ($all as $value => $label)
                    <div class="form-option">
                        {{ Form::checkbox($name . '['.$value.']', 1, in_array($value, $results), array('class' => 'form-option-field', 'id' => 'multiple-checkbox-' . $name . '-' .$value)) }}
                        <label class="form-option-label" for="multiple-checkbox-{{ $name }}-{{ $value }}">
                            {{ $label }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @include('shapeshifter::layouts.helptext')
        </div>
    </fieldset>
</div>
