<div class="form-group">
    <div class="form-label">
        {{ Form::label($label) }}
    </div>
    <div class="form-field">
        <div class="form-control">
            <div class="">
            {{
                Form::text($name, '', array(
                    isset($flags[0]) && $flags[0] == 'readonly' ? 'readonly' : '',
                    'class' => 'form-control tokeninput',
                    'id' => $name,
                    'data-prepopulate' => $results,
                    'data-allresults' => $all
                ))
            }}
            </div>
        </div>
        @include('shapeshifter::layouts.helptext')
    </div>
</div>