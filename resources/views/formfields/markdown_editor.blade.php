<textarea
    class="form-control easymde"
    name="{{ $row->field }}"
    id="markdown{{ $row->field }}"
    data-type="{{ isset($view) ? $view : 'edit' }}"
    @if(isset($view) && $view == 'read') disabled @endif
>{{ old($row->field, $dataTypeContent->{$row->field} ?? '') }}</textarea>
