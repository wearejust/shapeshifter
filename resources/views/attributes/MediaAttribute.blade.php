<div class="js-media-container">
    <label class="form-group js-placeholder" for="{{ $name }}">
        <span class="form-group-content">
            <span class="form-label">
                {{ $label }}
            </span>
            <span class="form-field">
                <span class="form-control">
                    {!! Form::file($name, array('class' => 'form-field-content' . ($required ?' js-required':''), 'id' => $name)) !!}
                    <span class="form-group-highlight"></span>
                </span>
                @include('shapeshifter::layouts.helptext')
            </span>
        </span>
    </label>

    @if ($value)
        <div class="form-group js-media-container-image">
            <span class="form-group-content">
                <span class="form-label" style="float:left;"></span>
                <span class="form-field" style="float: right;">
                    <span class="form-control">
                        <a href="#" class="form-field-media-delete">Verwijderen</a>
                        {!! Html::image($value->getUrl()) !!}
                    </span>
                </span>
            </span>
        </div>
    @endif
</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.form-field-media-delete').on('click', function(e) {
                e.preventDefault();

                var container = $(this).closest('.js-media-container');
                var id = container.find('input[type="file"]').attr('id');
                container.append(
                    '<input type="hidden" name="'+ id +'_remove" value="true">'
                );
                container.find('.js-media-container-image').remove();
            });
        });
    </script>
@stop
