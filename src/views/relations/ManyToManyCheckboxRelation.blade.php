<div class="form-group form-group-options">
    <span class="form-group-content">
        <fieldset>
            <legend class="form-label wrap section-end">
                <span class="form-legend">{{ $label }}</span>
            </legend>
            <div class="form-field radio">
                <div class="form-options">
                    @foreach ($all as $value => $label)
                    <div class="section form-option">
                        {{ Form::checkbox($name . '['.$value.']', 1, in_array($value, $results), array('class' => 'form-option-field', 'id' => 'multiple-checkbox-' . $name . '-' .$value)) }}
                        <label class="form-option-label" for="multiple-checkbox-{{ $name }}-{{ $value }}">
                            {{ $label }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @include('shapeshifter::layouts.helptext')
            </div>
        </fieldset>
    </span>
</div>