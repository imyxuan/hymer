<input type="date" class="form-control datepicker" name="{{ $row->field }}"
       data-type="date"
       placeholder="{{ $row->getTranslatedAttribute('display_name') }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ \Carbon\Carbon::parse(old($row->field, $dataTypeContent->{$row->field}))->format('Y-m-d') }}@else{{old($row->field)}}@endif">
