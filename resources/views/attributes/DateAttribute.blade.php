<div class="form-group" >
    <span class="form-group-content">
        <label for="{{$name}}" class="form-label">
            {{ $label }}
        </label>
        <span class="form-field">
            <p class="datepicker input-group" data-wrap="true">
                <input placeholder="{{ date('d-m-Y') }}"
                       value="{{ $model->{$name} }}"
                       name="{{ $name }}"
                       class="{{ ($required ? ' js-required':'') }}"
                       id="{{ $name }}"
                       autocorrect="off"
                       data-input>
            </p>
        </span>
    </span>
</div>
