<div
    id="{{ $row->field }}"
    data-theme="{{ @$options->theme }}"
    data-language="{{ @$options->language }}"
    class="ace-editor min_height_200"
>
    {{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}
</div>
<textarea
    name="{{ $row->field }}"
    id="{{ $row->field }}_textarea"
    class="d-none">{{ old($row->field, $dataTypeContent->{$row->field} ?? $options->default ?? '') }}
</textarea>
