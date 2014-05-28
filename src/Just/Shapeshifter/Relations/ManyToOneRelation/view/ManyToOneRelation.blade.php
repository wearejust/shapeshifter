<div class="form-group">
    <div class="col-3">
        {{ Form::label($name, $label) }}
    </div>
    <div class="col-9">
        <div class="form-control">
            <div class="col-6">
                {{ Form::select($name, $select)  }}
            </div>
        </div>
        @include('shapeshifter::layouts.helptext')
    </div>
</div>

