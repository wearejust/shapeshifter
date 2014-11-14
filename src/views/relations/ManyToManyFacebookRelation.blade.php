<label class="form-group js-placeholder" for="{{$name}}">
    <span class="form-group-content">
        <span class="form-label">
            {{ $label }}
        </span>
        <span class="form-field">
          <div class="form-control">
              <div class="input" style="cursor: text;">
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
        </span>
    </span>
</label>