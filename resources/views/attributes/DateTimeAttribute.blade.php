<style>
    .datepicker input {
        width: 40%;
    }
    .datepicker .btn {
        border: 1px solid #dadada;
        border-left: none;
        font-size:1.12em;
        padding: 0.7em 0.8em;
    }
</style>

<div class="form-group" for="{{$name}}">
    <span class="form-group-content">
        <label for="" class="form-label">
            {{ $label }}
        </label>
        <span class="form-field">

            <p class="datepicker input-group" data-wrap="true" data-clickOpens="false" data-enableTime="true">

                <input placeholder="{{ date('d-m-Y H:i') }}"
                       maxlength="0"
                       value="{{ $model->{$name} }}"
                       name="{{ $name }}"
                       class="{{ ($required ? ' js-required':'') }}"
                       id="{{ $name }}"
                       autocorrect="off"
                       data-input><!--
                --><a class="btn" data-toggle><i class="fa fa-calendar"></i></a><!--
                --><a class="btn" data-clear><i class="fa fa-close"></i> </a>

            </p>

        </span>
    </span>
</div>
