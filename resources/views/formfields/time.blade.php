<input @if($row->required == 1) required @endif type="time"
       data-name="{{ $row->getTranslatedAttribute('display_name') }}"
       class="form-control datepicker" name="{{ $row->field }}"
       data-type="time"
       placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}">
