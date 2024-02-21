<?php $selected_value = old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? NULL) ?>
<ul class="radio">
    @if(isset($options->options))
        @foreach($options->options as $key => $option)
            <li>
                <input type="radio" id="option-{{ Str::slug($row->field, '-') }}-{{ Str::slug($key, '-') }}"
                       name="{{ $row->field }}"
                       value="{{ $key }}" @if($selected_value == $key) checked @endif>
                <label for="option-{{ Str::slug($row->field, '-') }}-{{ Str::slug($key, '-') }}">{{ $option }}</label>
                <div class="check"></div>
            </li>
        @endforeach
    @endif
</ul>
