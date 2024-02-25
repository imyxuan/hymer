<input @if($row->required == 1) required @endif type="text" class="form-control datepicker"
       name="{{ $row->field }}"
       data-type="datetime"
       data-value="{{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('Y-m-d H:i:s') }}"
       value="{{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('Y-m-d H:i:s') }}">
