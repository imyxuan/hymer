@if(isset($options->model) && isset($options->type))

    @if(class_exists($options->model))

        @php $relationshipField = $row->field; @endphp

        @if($options->type == 'belongsTo')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                    $query = $model::where($options->key,$relationshipData->{$options->column})->first();
                @endphp

                @if(isset($query))
                    <p>{{ $query->{$options->label} }}</p>
                @else
                    <p>{{ __('hymer::generic.no_results') }}</p>
                @endif

            @else

                <select
                    class="form-control select2-ajax" name="{{ $options->column }}"
                    data-get-items-route="{{route('hymer.' . $dataType->slug.'.relation')}}"
                    data-get-items-field="{{$row->field}}"
                    @if(!is_null($dataTypeContent->getKey())) data-id="{{$dataTypeContent->getKey()}}" @endif
                    data-method="{{ !is_null($dataTypeContent->getKey()) ? 'edit' : 'add' }}"
                    @if($row->required == 1) required @endif
                >
                    @php
                        $model = app($options->model);
                        $query = $model::where($options->key, old($options->column, $dataTypeContent->{$options->column}))->get();
                    @endphp

                    @if(!$row->required)
                        <option value="">{{__('hymer::generic.none')}}</option>
                    @endif

                    @foreach($query as $relationshipData)
                        <option value="{{ $relationshipData->{$options->key} }}" @if(old($options->column, $dataTypeContent->{$options->column}) == $relationshipData->{$options->key}) selected="selected" @endif>{{ $relationshipData->{$options->label} }}</option>
                    @endforeach
                </select>

            @endif

        @elseif($options->type == 'hasOne')

            @php
                $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                $model = app($options->model);
                $query = $model::where($options->column, '=', $relationshipData->{$options->key})->first();

            @endphp

            @if(isset($query))
                <p>{{ $query->{$options->label} }}</p>
            @else
                <p>{{ __('hymer::generic.no_results') }}</p>
            @endif

        @elseif($options->type == 'hasMany')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;
                    $model = app($options->model);
                @endphp

                @if($view == 'browse')
                    @php
                        $count = $model->count()
                    @endphp
                    @if($count == 0)
                        <p>{{ __('hymer::generic.no_results') }}</p>
                    @else
                        <a href="#">{{ __('hymer::generic.count') }}: {{ $count }}</a>
                    @endif
                @else
                    @php
                        $items = $row->items;
                        $dRows = $row->dataRows;
                    @endphp
                    @if(empty($items))
                        <p>{{ __('hymer::generic.no_results') }}</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                @foreach($dRows as $dRow)
                                <th>{{ $dRow->display_name }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                            <tr>
                                @foreach($dRows as $dRow)
                                    <td>{{ $item->{$dRow->field} }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{ $items->links() }}
                        </div>
                    @endif
                @endif

            @else

                @php
                    $model = app($options->model);
                    $query = $model::where($options->column, '=', $dataTypeContent->{$options->key})->get();
                @endphp

                @if($query->isNotEmpty())
                    <ul>
                        @foreach($query as $query_res)
                            <li>{{ $query_res->{$options->label} }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{{ __('hymer::generic.no_results') }}</p>
                @endif

            @endif

        @elseif($options->type == 'belongsToMany')

            @if(isset($view) && ($view == 'browse' || $view == 'read'))

                @php
                    $relationshipData = (isset($data)) ? $data : $dataTypeContent;

                    $selected_values = isset($relationshipData) ? $relationshipData->belongsToMany($options->model, $options->pivot_table, $options->foreign_pivot_key ?? null, $options->related_pivot_key ?? null, $options->parent_key ?? null, $options->key)->get()->map(function ($item, $key) use ($options) {
            			return $item->{$options->label};
            		})->all() : array();
                @endphp

                @if($view == 'browse')
                    @php
                        $string_values = implode(", ", $selected_values);
                        if(mb_strlen($string_values) > 25){ $string_values = mb_substr($string_values, 0, 25) . '...'; }
                    @endphp
                    @if(empty($selected_values))
                        <p>{{ __('hymer::generic.no_results') }}</p>
                    @else
                        <p>{{ $string_values }}</p>
                    @endif
                @else
                    @if(empty($selected_values))
                        <p>{{ __('hymer::generic.no_results') }}</p>
                    @else
                        <ul>
                            @foreach($selected_values as $selected_value)
                                <li>{{ $selected_value }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif

            @else
                <select
                    class="form-control select2-ajax @if(isset($options->taggable) && $options->taggable === 'on') taggable @endif"
                    name="{{ $relationshipField }}[]" multiple
                    data-get-items-route="{{route('hymer.' . $dataType->slug.'.relation')}}"
                    data-get-items-field="{{$row->field}}"
                    @if(!is_null($dataTypeContent->getKey())) data-id="{{$dataTypeContent->getKey()}}" @endif
                    data-method="{{ !is_null($dataTypeContent->getKey()) ? 'edit' : 'add' }}"
                    @if(isset($options->taggable) && $options->taggable === 'on')
                        data-route="{{ route('hymer.'.Str::slug($options->table).'.store') }}"
                        data-label="{{$options->label}}"
                        data-error-message="{{__('hymer::bread.error_tagging')}}"
                    @endif
                    @if($row->required == 1) required @endif
                >

                        @php
                            $selected_keys = [];

                            if (!is_null($dataTypeContent->getKey())) {
                                $selected_keys = $dataTypeContent->belongsToMany(
                                    $options->model,
                                    $options->pivot_table,
                                    $options->foreign_pivot_key ?? null,
                                    $options->related_pivot_key ?? null,
                                    $options->parent_key ?? null,
                                    $options->key
                                )->pluck($options->table.'.'.$options->key);
                            }
                            $selected_keys = old($relationshipField, $selected_keys);
                            $selected_values = app($options->model)->whereIn($options->key, $selected_keys)->pluck($options->label, $options->key);
                        @endphp

                        @if(!$row->required)
                            <option value="">{{__('hymer::generic.none')}}</option>
                        @endif

                        @foreach ($selected_values as $key => $value)
                            <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                        @endforeach

                </select>

            @endif

        @endif

    @else

        cannot make relationship because {{ $options->model }} does not exist.

    @endif

@endif
