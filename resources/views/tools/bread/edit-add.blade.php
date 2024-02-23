@extends('hymer::master')

@if (isset($dataType->id))
    @section('page_title', __('hymer::bread.edit_bread_for_table', ['table' => $dataType->name]))
    @php
        $display_name = $dataType->getTranslatedAttribute('display_name_singular');
        $display_name_plural = $dataType->getTranslatedAttribute('display_name_plural');
    @endphp
@else
    @section('page_title', __('hymer::bread.create_bread_for_table', ['table' => $table]))
@endif

@section('page_header')
    <div class="page-title d-flex align-items-center">
        <i class="hymer-data"></i>
        <span>
            @if (isset($dataType->id))
                {{ __('hymer::bread.edit_bread_for_table', ['table' => $dataType->name]) }}
            @else
                {{ __('hymer::bread.create_bread_for_table', ['table' => $table]) }}
            @endif
        </span>
    </div>
    @php
        $isModelTranslatable = (!isset($isModelTranslatable) || !isset($dataType)) ? false : $isModelTranslatable;
        if (isset($dataType->name)) {
            $table = $dataType->name;
        }
    @endphp
    @include('hymer::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content container-fluid" id="hymerBreadEditAdd">
        <div class="row">
            <div class="col-12">

                <form
                    action="
                        @if(isset($dataType->id))
                            {{ route('hymer.bread.update', $dataType->id) }}
                        @else
                            {{ route('hymer.bread.store') }}
                        @endif
                    "
                    method="POST" role="form">
                @if(isset($dataType->id))
                    <input type="hidden" value="{{ $dataType->id }}" name="id">
                    {{ method_field("PUT") }}
                @endif
                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="accordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header panel-icon">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#baseInfo">
                                    <i class="hymer-bread"></i>
                                    <span class="ms-2">{{ ucfirst($table) }} {{ __('hymer::bread.bread_info') }}</span>
                                </button>
                            </h2>
                        </div>
                        <div class="accordion-collapse collapse show p-3" id="baseInfo">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="name">{{ __('hymer::database.table_name') }}</label>
                                    <input
                                        type="text" class="form-control" readonly name="name"
                                        placeholder="{{ __('generic_name') }}"
                                        value="{{ $dataType->name ?? $table }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="display_name_singular">
                                        {{ __('hymer::bread.display_name_singular') }}
                                    </label>
                                    @if($isModelTranslatable)
                                        @include('hymer::multilingual.input-hidden', [
                                            'isModelTranslatable' => true,
                                            '_field_name'         => 'display_name_singular',
                                            '_field_trans' => get_field_translations($dataType, 'display_name_singular')
                                        ])
                                    @endif
                                    <input type="text" class="form-control"
                                           name="display_name_singular"
                                           id="display_name_singular"
                                           placeholder="{{ __('hymer::bread.display_name_singular') }}"
                                           value="{{ $display_name }}">
                                </div>
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="display_name_plural">
                                        {{ __('hymer::bread.display_name_plural') }}
                                    </label>
                                    @if($isModelTranslatable)
                                        @include('hymer::multilingual.input-hidden', [
                                            'isModelTranslatable' => true,
                                            '_field_name'         => 'display_name_plural',
                                            '_field_trans' => get_field_translations($dataType, 'display_name_plural')
                                        ])
                                    @endif
                                    <input type="text" class="form-control"
                                           name="display_name_plural"
                                           id="display_name_plural"
                                           placeholder="{{ __('hymer::bread.display_name_plural') }}"
                                           value="{{ $display_name_plural }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="slug">{{ __('hymer::bread.url_slug') }}</label>
                                    <input
                                        type="text" class="form-control" name="slug"
                                        placeholder="{{ __('hymer::bread.url_slug_ph') }}"
                                        value="{{ $dataType->slug ?? $slug }}">
                                </div>
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="icon">
                                        <span>{{ __('hymer::bread.icon_hint') }}</span>
                                        <a
                                            href="{{ route('hymer.compass.index') }}#fonts"
                                            target="_blank">
                                            {{ __('hymer::bread.icon_hint2') }}
                                        </a>
                                    </label>
                                    <input type="text" class="form-control" name="icon"
                                           placeholder="{{ __('hymer::bread.icon_class') }}"
                                           value="{{ $dataType->icon ?? '' }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="model_name">
                                        {{ __('hymer::bread.model_name') }}
                                    </label>
                                    <span
                                        class="hymer-question"
                                        type="button"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="{{ __('hymer::bread.model_name_ph') }}"
                                    >
                                    </span>
                                    <input type="text" class="form-control" name="model_name"
                                           placeholder="{{ __('hymer::bread.model_class') }}"
                                           value="{{ $dataType->model_name ?? $model_name }}">
                                </div>
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="controller">
                                        {{ __('hymer::bread.controller_name') }}
                                    </label>
                                    <span class="hymer-question"
                                        aria-hidden="true"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="{{ __('hymer::bread.controller_name_hint') }}"></span>
                                    <input type="text" class="form-control" name="controller"
                                           placeholder="{{ __('hymer::bread.controller_name') }}"
                                           value="{{ $dataType->controller ?? '' }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-6 col-6">
                                    <label class="form-label" for="policy_name">
                                        {{ __('hymer::bread.policy_name') }}
                                    </label>
                                    <span class="hymer-question"
                                          aria-hidden="true"
                                          data-bs-toggle="tooltip"
                                          data-bs-placement="right"
                                          title="{{ __('hymer::bread.policy_name_ph') }}"></span>
                                    <input type="text" class="form-control" name="policy_name"
                                           placeholder="{{ __('hymer::bread.policy_class') }}"
                                           value="{{ $dataType->policy_name ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="generate_permissions">
                                        {{ __('hymer::bread.generate_permissions') }}
                                    </label>
                                    <br>
                                    <?php $checked = (isset($dataType->generate_permissions) &&
                                            $dataType->generate_permissions == 1) ||
                                        (isset($generate_permissions) && $generate_permissions); ?>
                                    <input type="checkbox"
                                           name="generate_permissions"
                                           class="form-check form-switch"
                                           data-on="{{ __('hymer::generic.yes') }}"
                                           data-off="{{ __('hymer::generic.no') }}"
                                           @if($checked) checked @endif >
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="server_side">
                                        {{ __('hymer::bread.server_pagination') }}
                                    </label>
                                    <br>
                                    <?php $checked = (isset($dataType->server_side) && $dataType->server_side == 1) ||
                                        (isset($server_side) && $server_side); ?>
                                    <input type="checkbox"
                                           name="server_side"
                                           class="form-check form-switch"
                                           data-on="{{ __('hymer::generic.yes') }}"
                                           data-off="{{ __('hymer::generic.no') }}"
                                           @if($checked) checked @endif >
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label" for="order_column">
                                        {{ __('hymer::bread.order_column') }}
                                    </label>
                                    <span class="hymer-question"
                                          aria-hidden="true"
                                          data-bs-toggle="tooltip"
                                          data-placement="right"
                                          title="{{ __('hymer::bread.order_column_ph') }}"></span>
                                    <select name="order_column" class="select2 form-control">
                                        <option value="">-- {{ __('hymer::generic.none') }} --</option>
                                        @foreach($fieldOptions as $tbl)
                                        <option value="{{ $tbl['field'] }}"
                                                @if(
                                                    isset($dataType) && $dataType->order_column == $tbl['field']
                                                ) selected @endif
                                        >{{ $tbl['field'] }}</option>
                                        @endforeach
                                      </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="order_display_column">
                                        {{ __('hymer::bread.order_ident_column') }}
                                    </label>
                                    <span class="hymer-question"
                                          aria-hidden="true"
                                          data-bs-toggle="tooltip"
                                          data-placement="right"
                                          title="{{ __('hymer::bread.order_ident_column_ph') }}"></span>
                                    <select name="order_display_column" class="select2 form-control">
                                        <option value="">-- {{ __('hymer::generic.none') }} --</option>
                                        @foreach($fieldOptions as $tbl)
                                        <option value="{{ $tbl['field'] }}"
                                                @if(
                                                    isset($dataType) && $dataType->order_display_column == $tbl['field']
                                                ) selected @endif
                                        >{{ $tbl['field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="order_direction">
                                        {{ __('hymer::bread.order_direction') }}
                                    </label>
                                    <select name="order_direction" class="select2 form-control">
                                        <option
                                            value="asc"
                                            @if(isset($dataType) && $dataType->order_direction == 'asc') selected @endif
                                        >
                                            {{ __('hymer::generic.ascending') }}
                                        </option>
                                        <option
                                            value="desc"
                                            @if(isset($dataType) && $dataType->order_direction == 'desc')
                                                selected
                                            @endif
                                        >
                                            {{ __('hymer::generic.descending') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="default_search_key">
                                        {{ __('hymer::bread.default_search_key') }}
                                    </label>
                                    <span class="hymer-question"
                                          aria-hidden="true"
                                          data-bs-toggle="tooltip"
                                          data-placement="right"
                                          title="{{ __('hymer::bread.default_search_key_ph') }}"></span>
                                    <select name="default_search_key" class="select2 form-control">
                                        <option value="">-- {{ __('hymer::generic.none') }} --</option>
                                        @foreach($fieldOptions as $tbl)
                                        <option
                                            value="{{ $tbl['field'] }}"
                                            @if(isset($dataType) && $dataType->default_search_key == $tbl['field'])
                                                selected
                                            @endif
                                        >{{ $tbl['field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                @if (isset($scopes) && isset($dataType))
                                    <div class="col-md-3">
                                        <label class="form-label" for="scope">{{ __('hymer::bread.scope') }}</label>
                                        <select name="scope" class="select2 form-control">
                                            <option value="">-- {{ __('hymer::generic.none') }} --</option>
                                            @foreach($scopes as $scope)
                                            <option value="{{ $scope }}"
                                                    @if($dataType->scope == $scope) selected @endif
                                            >{{ $scope }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-9">
                                    <label class="form-label" for="description">
                                        {{ __('hymer::bread.description') }}
                                    </label>
                                    <textarea class="form-control"
                                              name="description"
                                              placeholder="{{ __('hymer::bread.description') }}"
                                    >{{ $dataType->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion mt-4">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#rowInfo">
                                    <i class="hymer-window-list"></i>
                                    <span class="ms-2">{{ __('hymer::bread.edit_rows', ['table' => $table]) }}:</span>
                                </button>
                            </h2>
                        </div>
                        <div class="accordion-collapse collapse show p-3" id="rowInfo">
                            <div class="row fake-table-hd">
                                <div class="col-2">{{ __('hymer::database.field') }}</div>
                                <div class="col-2">{{ __('hymer::database.visibility') }}</div>
                                <div class="col-2">{{ __('hymer::database.input_type') }}</div>
                                <div class="col-2">{{ __('hymer::bread.display_name') }}</div>
                                <div class="col-4">{{ __('hymer::database.optional_details') }}</div>
                            </div>

                            <div id="bread-items">
                            @php
                                $r_order = 0;
                            @endphp
                            @foreach($fieldOptions as $data)
                                @php
                                    $r_order += 1;
                                @endphp

                                @if(isset($dataType->id))
                                    <?php
                                        $dataRow = Hymer::model('DataRow')
                                            ->where('data_type_id', '=', $dataType->id)
                                            ->where('field', '=', $data['field'])->first();
                                    ?>
                                @endif

                                <div class="row row-dd">
                                    <div class="col-2 position-relative">
                                        <h4><strong>{{ $data['field'] }}</strong></h4>
                                        <strong>{{ __('hymer::database.type') }}:</strong>
                                        <span>{{ $data['type'] }}</span>
                                        <br/>
                                        <strong>{{ __('hymer::database.key') }}:</strong>
                                        <span>{{ $data['key'] }}</span>
                                        <br/>
                                        <strong>{{ __('hymer::generic.required') }}:</strong>
                                        @if($data['null'] == "NO")
                                            <span>{{ __('hymer::generic.yes') }}</span>
                                            <input
                                                type="hidden" value="1"
                                                name="field_required_{{ $data['field'] }}" checked="checked">
                                        @else
                                            <span>{{ __('hymer::generic.no') }}</span>
                                            <input type="hidden" value="0" name="field_required_{{ $data['field'] }}">
                                        @endif
                                        <div class="handler hymer-handle"></div>
                                        <input
                                            class="row_order" type="hidden"
                                            value="{{ $dataRow->order ?? $r_order }}"
                                            name="field_order_{{ $data['field'] }}">
                                    </div>
                                    <div class="col-2">
                                        <input type="checkbox"
                                               id="field_browse_{{ $data['field'] }}"
                                               name="field_browse_{{ $data['field'] }}"
                                               @if(isset($dataRow->browse) && $dataRow->browse)
                                                   checked="checked"
                                               @elseif($data['key'] == 'PRI')
                                               @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                               @elseif(!isset($dataRow->browse))
                                                   checked="checked"
                                               @endif>
                                        <label for="field_browse_{{ $data['field'] }}">
                                            {{ __('hymer::generic.browse') }}
                                        </label>
                                        <br/>
                                        <input
                                            type="checkbox"
                                            id="field_read_{{ $data['field'] }}"
                                            name="field_read_{{ $data['field'] }}"
                                            @if(isset($dataRow->read) && $dataRow->read)
                                                checked="checked"
                                            @elseif($data['key'] == 'PRI')
                                            @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                            @elseif(!isset($dataRow->read))
                                                checked="checked"
                                            @endif
                                        >
                                        <label for="field_read_{{ $data['field'] }}">
                                            {{ __('hymer::generic.read') }}
                                        </label>
                                        <br/>
                                        <input
                                            type="checkbox"
                                            id="field_edit_{{ $data['field'] }}"
                                            name="field_edit_{{ $data['field'] }}"
                                            @if(isset($dataRow->edit) && $dataRow->edit)
                                                checked="checked"
                                            @elseif($data['key'] == 'PRI')
                                            @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                            @elseif(!isset($dataRow->edit))
                                                checked="checked"
                                            @endif
                                        >
                                        <label for="field_edit_{{ $data['field'] }}">
                                            {{ __('hymer::generic.edit') }}
                                        </label>
                                        <br/>
                                        <input
                                            type="checkbox"
                                            id="field_add_{{ $data['field'] }}"
                                            name="field_add_{{ $data['field'] }}"
                                            @if(isset($dataRow->add) && $dataRow->add)
                                                checked="checked"
                                            @elseif($data['key'] == 'PRI')
                                            @elseif($data['type'] == 'timestamp' && $data['field'] == 'created_at')
                                            @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                            @elseif(!isset($dataRow->add))
                                                checked="checked"
                                            @endif
                                        >
                                        <label for="field_add_{{ $data['field'] }}">
                                            {{ __('hymer::generic.add') }}
                                        </label>
                                        <br/>
                                        <input
                                            type="checkbox"
                                            id="field_delete_{{ $data['field'] }}"
                                            name="field_delete_{{ $data['field'] }}"
                                            @if(isset($dataRow->delete) && $dataRow->delete)
                                                checked="checked"
                                            @elseif($data['key'] == 'PRI')
                                            @elseif($data['type'] == 'timestamp' && $data['field'] == 'updated_at')
                                            @elseif(!isset($dataRow->delete))
                                                checked="checked"
                                            @endif
                                        >
                                        <label for="field_delete_{{ $data['field'] }}">
                                            {{ __('hymer::generic.delete') }}
                                        </label>
                                        <br/>
                                    </div>
                                    <div class="col-2">
                                        <input
                                            type="hidden" name="field_{{ $data['field'] }}"
                                            value="{{ $data['field'] }}"
                                        >
                                        @if($data['type'] == 'timestamp')
                                            <p>{{ __('hymer::generic.timestamp') }}</p>
                                            <input type="hidden" value="timestamp"
                                                   name="field_input_type_{{ $data['field'] }}">
                                        @else
                                            <select class="form-control" name="field_input_type_{{ $data['field'] }}">
                                                @foreach (Hymer::formFields() as $formField)
                                                    @php
                                                    $selected = (
                                                        isset($dataRow->type) &&
                                                        $formField->getCodename() == $dataRow->type
                                                    ) || (
                                                        !isset($dataRow->type) &&
                                                        $formField->getCodename() == 'text'
                                                    );
                                                    @endphp
                                                    <option
                                                        value="{{ $formField->getCodename() }}"
                                                        {{ $selected ? 'selected' : '' }}
                                                    >
                                                        {{ $formField->getName() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        @if($isModelTranslatable)
                                            @include('hymer::multilingual.input-hidden', [
                                                'isModelTranslatable' => true,
                                                '_field_name'         => 'field_display_name_' . $data['field'],
                                                '_field_trans' => $dataRow ?
                                                    get_field_translations($dataRow, 'display_name') :
                                                    json_encode(
                                                        [
                                                            config('hymer.multilingual.default') =>
                                                                ucwords(str_replace('_', ' ', $data['field']))
                                                        ]
                                                    ),
                                            ])
                                        @endif
                                        <input
                                            type="text" class="form-control"
                                            value="{{ $dataRow->display_name ?? ucwords(str_replace('_', ' ', $data['field'])) }}"
                                            name="field_display_name_{{ $data['field'] }}">
                                    </div>
                                    <div class="col-4">
                                        <div class="alert alert-danger validation-error">
                                            {{ __('hymer::json.invalid') }}
                                        </div>
                                        <textarea id="json-input-{{ json_encode($data['field']) }}"
                                                  class="resizable-editor"
                                                  data-editor="json"
                                                  name="field_details_{{ $data['field'] }}">
                                            {{ json_encode(isset($dataRow->details) ? $dataRow->details : new class{}) }}
                                        </textarea>
                                    </div>
                                </div>



                            @endforeach

                            @if(isset($dataTypeRelationships))
                                @foreach($dataTypeRelationships as $relationship)
                                    @include('hymer::tools.bread.relationship-partial', $relationship)
                                @endforeach
                            @endif

                            </div>

                        </div>
                        <div class="panel-footer">
                             <div class="btn btn-danger btn-new-relationship">
                                 <i class="hymer-heart"></i>
                                 <span>
                                     {{ __('hymer::database.relationship.create') }}
                                 </span>
                             </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 float-end ">{{ __('hymer::generic.submit') }}</button>

                </form>
            </div><!-- .col-md-12 -->
        </div><!-- .row -->
    </div><!-- .page-content -->

@include('hymer::tools.bread.relationship-new-modal')

@stop

@section('javascript')
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.min.css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script>
        window.invalidEditors = []
        const validationAlerts = $('.validation-error')
        validationAlerts.hide()
        $(function () {
            @if ($isModelTranslatable)
                /**
                 * Multilingual setup
                 */
                $('.side-body').multilingual({
                    "form":    'form',
                    "editing": true
                })
            @endif
            /**
             * Reorder items
             */
            reOrderItems();

            $('#bread-items').disableSelection()

            $('[data-bs-toggle="tooltip"]').tooltip()



            $('textarea[data-editor]').each(function () {
                var textarea = $(this),
                mode = textarea.data('editor'),
                editDiv = $('<div>').insertBefore(textarea),
                editor = ace.edit(editDiv[0]),
                _session = editor.getSession(),
                valid = false
                textarea.hide()

                // Validate JSON
                _session.on("changeAnnotation", function(){
                    valid = _session.getAnnotations().length ? false : true
                    if (!valid) {
                        if (window.invalidEditors.indexOf(textarea.attr('id')) < 0) {
                            window.invalidEditors.push(textarea.attr('id'))
                        }
                    } else {
                        for(var i = window.invalidEditors.length - 1; i >= 0; i--) {
                            if(window.invalidEditors[i] == textarea.attr('id')) {
                               window.invalidEditors.splice(i, 1)
                            }
                        }
                    }
                })

                // Use workers only when needed
                editor.on('focus', function () {
                    _session.setUseWorker(true)
                })
                editor.on('blur', function () {
                    if (valid) {
                        textarea.siblings('.validation-error').hide()
                        _session.setUseWorker(false)
                    } else {
                        textarea.siblings('.validation-error').show()
                    }
                })

                _session.setUseWorker(false)

                editor.setAutoScrollEditorIntoView(true)
                editor.$blockScrolling = Infinity
                editor.setOption("maxLines", 30)
                editor.setOption("minLines", 4)
                editor.setOption("showLineNumbers", false)
                editor.setTheme("ace/theme/github")
                _session.setMode("ace/mode/json")
                if (textarea.val()) {
                    _session.setValue(JSON.stringify(JSON.parse(textarea.val()), null, 4))
                }

                _session.setMode("ace/mode/" + mode)

                // copy back to textarea on form submit...
                textarea.closest('form').on('submit', function (ev) {
                    if (window.invalidEditors.length) {
                        ev.preventDefault()
                        ev.stopPropagation()
                        validationAlerts.hide()
                        for (var i = window.invalidEditors.length - 1; i >= 0; i--) {
                            $('#'+window.invalidEditors[i]).siblings('.validation-error').show()
                        }
                        toastr.error(
                            '{{ __('hymer::json.invalid_message') }}',
                            '{{ __('hymer::json.validation_errors') }}',
                            {"preventDuplicates": true, "preventOpenDuplicates": true}
                        )
                    } else {
                        if (_session.getValue()) {
                            // uglify JSON object and update textarea for submit purposes
                            textarea.val(JSON.stringify(JSON.parse(_session.getValue())))
                        }else{
                            textarea.val('')
                        }
                    }
                })
            })

        })

        function reOrderItems(){
            $('#bread-items').sortable({
                handle: '.handler',
                update: function (e, ui) {
                    var _rows = $('#bread-items').find('.row_order'),
                        _size = _rows.length;

                    for (var i = 0; i < _size; i++) {
                        $(_rows[i]).val(i+1);
                    }
                },
                create: function (event, ui) {
                    sort();
                }
            });
        }

        function sort() {
            var sortableList = $('#bread-items');
            var listitems = $('div.row.row-dd', sortableList);

            listitems.sort(function (a, b) {
                return (parseInt($(a).find('.row_order').val()) > parseInt($(b).find('.row_order').val()))  ? 1 : -1;
            });
            sortableList.append(listitems);

        }

        /********** Relationship functionality **********/

        $(function () {
            $('.relationship_type').select2({})
            $('.relationship_table').select2({})
            $('.relationshipPivot select').select2({})
            $('.new_relationship_field').select2({})
            $('select.relationship_key').select2({})
            $('.relationship_type').change(function(){
                $(this).parent().parent().parent().find('.belongsToManyShow, .belongsToShow, .hasOneShow, .hasManyShow').hide();
                $(this).parent().parent().parent().find('.' + $(this).val() + 'Show').show();
                // hasOneShow has a prepopulated select, only one between the following should be enabled
                $(this).parent().parent().parent().find('.hasOneShow select').attr('disabled', true);
                $(this).parent().parent().parent().find('.belongsToShow select').attr('disabled', false);

                if($(this).val() == 'belongsTo'){
                    $(this).parent().parent().parent().find('.relationshipField').show();
                    $(this).parent().parent().parent().find('.relationshipPivot').hide();
                    $(this).parent().parent().parent().find('.relationship_taggable').hide();
                    $(this).parent().parent().parent().find('.hasOneMany').removeClass('flexed');
                    $(this).parent().parent().parent().find('.belongsTo').addClass('flexed');
                } else if($(this).val() == 'hasOne' || $(this).val() == 'hasMany'){
                    $(this).parent().parent().parent().find('.relationshipField').show();
                    $(this).parent().parent().parent().find('.relationshipPivot').hide();
                    $(this).parent().parent().parent().find('.relationship_taggable').hide();
                    $(this).parent().parent().parent().find('.hasOneMany').addClass('flexed');
                    $(this).parent().parent().parent().find('.belongsTo').removeClass('flexed');
                    $(this).parent().parent().parent().find('.hasOneShow select').attr('disabled', false);
                    $(this).parent().parent().parent().find('.belongsToShow select').attr('disabled', true);
                } else {
                    $(this).parent().parent().parent().find('.relationshipField').hide();
                    $(this).parent().parent().parent().find('.relationshipPivot').css('display', 'flex');
                    $(this).parent().parent().parent().find('.relationship_taggable').show();
                }
            }).trigger('change')

            $('.btn-new-relationship').click(function(){
                // Update table data
                $('#new_relationship_modal .relationship_table').trigger('change')

                const $modal = new bootstrap.Modal(document.getElementById('new_relationship_modal'))
                $modal.show()
            })

            relationshipTextDataBinding()

            $('.relationship_table').on('change', function(){
                populateRowsFromTable($(this))
            })

            $('.hymer-relationship-details-btn').click(function(){
                $(this).toggleClass('open')
                if($(this).hasClass('open')){
                    $(this).parent().parent().find('.hymer-relationship-details').slideDown()
                    populateRowsFromTable($(this).parent().parent().parent().find('select.relationship_table'))
                } else {
                    $(this).parent().parent().find('.hymer-relationship-details').slideUp()
                }
            })

        })

        function populateRowsFromTable(dropdown){
            const tbl = dropdown.val();

            $.get('{{ route('hymer.database.index') }}/' + tbl, function(data){
                const tbl_selected = $(dropdown).val()

                $(dropdown).parent().parent().parent().find('.rowDrop').each(function(){
                    let selected_value = $(this).data('selected');

                    const options = $.map(data, function (obj, key) {
                        return {id: key, text: key}
                    })

                    $(this).empty().select2({
                        data: options
                    })

                    if (selected_value == '' || !$(this).find("option[value='"+selected_value+"']").length) {
                        selected_value = $(this).find("option:first-child").val()
                    }

                    $(this).val(selected_value).trigger('change')
                })
            })
        }

        function relationshipTextDataBinding(){
            $('.relationship_display_name').bind('input', function() {
                $(this).parent().parent().parent().find('.label_relationship p').text($(this).val());
            });
            $('.relationship_table').on('change', function(){
                var tbl_selected_text = $(this).find('option:selected').text();
                $(this).parent().parent().parent().find('.label_table_name').text(tbl_selected_text);
            });
            $('.relationship_table').each(function(){
                var tbl_selected_text = $(this).find('option:selected').text();
                $(this).parent().parent().parent().find('.label_table_name').text(tbl_selected_text);
            });
        }

        $(document).on('click', '#submitNewRelationship', () => {
            $('#newRelationshipForm').submit()
        })
        /********** End Relationship Functionality **********/
    </script>
@stop
