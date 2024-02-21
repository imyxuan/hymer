@extends('hymer::master')

@section('page_title', __('hymer::generic.viewing').' '.__('hymer::generic.settings'))

@section('css')
    <style>
        .panel-actions .hymer-trash {
            cursor: pointer;
        }

        .panel-actions .hymer-trash:hover {
            color: #e94542;
        }

        .settings .panel-actions {
            right: 0px;
        }

        .panel {
            padding-bottom: 15px;
        }

        .sort-icons {
            font-size: 21px;
            color: #ccc;
            position: relative;
            cursor: pointer;
        }

        .sort-icons:hover {
            color: #37474F;
        }

        .hymer-sort-desc {
            margin-right: 10px;
        }

        .hymer-sort-asc {
            top: 10px;
        }

        .page-title {
            margin-bottom: 0;
        }

        .panel-title code {
            border-radius: 30px;
            padding: 5px 10px;
            font-size: 11px;
            border: 0;
            position: relative;
            top: -2px;
        }

        .modal-open .settings .select2-container {
            z-index: 9 !important;
            width: 100% !important;
        }

        .new-setting {
            position: relative;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .new-setting hr {
            position: absolute;
            top: 5px;
            width: calc(100% - 30px);
            margin-left: 15px;
        }

        .new-setting .panel-title {
            margin: 0 auto;
            display: inline-block;
            color: #999fac;
            font-weight: lighter;
            font-size: 13px;
            background: #fff;
            width: auto;
            height: auto;
            position: relative;
            padding-right: 15px;
        }

        .settings .panel-title {
            padding-left: 0;
            padding-right: 0;
        }

        .new-setting .divider {
            height: 1px;
            color: #eee;
        }

        .new-setting .panel-title i {
            position: relative;
            top: 2px;
        }

        .new-settings-options {
            display: none;
            padding-bottom: 10px;
        }

        .new-settings-options label {
            margin-top: 13px;
        }

        .new-settings-options .alert {
            margin-bottom: 0;
        }

        #toggle_options {
            clear: both;
            float: right;
            font-size: 12px;
            position: relative;
            margin-top: 15px;
            margin-right: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            z-index: 9;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .new-setting-btn {
            margin-right: 15px;
            position: relative;
            margin-bottom: 0;
            top: 5px;
        }

        .new-setting-btn i {
            position: relative;
            top: 2px;
        }

        textarea {
            min-height: 120px;
        }

        textarea.hidden {
            display: none;
        }

        .hymer .settings .nav-tabs .active a {
            border: 0px;
        }

        .select2 {
            width: 100% !important;
        }

        .hymer .settings input[type=file] {
            width: 100%;
        }

        .settings .select2-selection {
            height: 32px;
            padding: 2px;
        }

        .tab-content {
            background: #ffffff;
            border: 1px solid transparent;
        }

        .tab-content > div {
            padding: 20px;
        }
    </style>

@stop

@section('page_header')
    <h1 class="page-title d-flex align-items-center">
        <i class="hymer-settings"></i> {{ __('hymer::generic.settings') }}
    </h1>
@stop

@section('content')
    <div class="container-fluid">
        @include('hymer::alerts')
        @if(config('hymer.show_dev_tips'))
        <div class="alert alert-info">
            <strong>{{ __('hymer::generic.how_to_use') }}:</strong>
            <p>{{ __('hymer::settings.usage_help') }} <code>setting('group.key')</code></p>
        </div>
        @endif
    </div>

    <div class="page-content settings container-fluid">
        <form action="{{ route('hymer.settings.update') }}" method="POST" enctype="multipart/form-data">
            {{ method_field("PUT") }}
            {{ csrf_field() }}
            <input type="hidden" name="setting_tab" class="setting_tab" value="{{ $active }}" />
            <div class="panel">

                <div class="page-content settings container-fluid">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($settings as $group => $setting)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link @if($group == $active) active @endif"
                                   data-bs-toggle="tab"
                                   role="button"
                                   href="#{{ Str::slug($group) }}">{{ $group }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($settings as $group => $group_settings)
                        <div id="{{ Str::slug($group) }}"
                             class="tab-pane fade @if($group == $active) show active @endif"
                             role="tabpanel"
                        >
                            @foreach($group_settings as $setting)
                            <div class="panel-heading d-flex justify-content-between align-items-center">
                                <h3 class="panel-title">
                                    {{ $setting->display_name }} @if(config('hymer.show_dev_tips'))<code>setting('{{ $setting->key }}')</code>@endif
                                </h3>
                                <div class="panel-actions">
                                    <a href="{{ route('hymer.settings.move_up', $setting->id) }}">
                                        <i class="sort-icons hymer-sort-asc"></i>
                                    </a>
                                    <a href="{{ route('hymer.settings.move_down', $setting->id) }}">
                                        <i class="sort-icons hymer-sort-desc"></i>
                                    </a>
                                    @can('delete', Hymer::model('Setting'))
                                    <i class="hymer-trash"
                                       data-id="{{ $setting->id }}"
                                       data-display-key="{{ $setting->key }}"
                                       data-display-name="{{ $setting->display_name }}"></i>
                                    @endcan
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-10 ps-0">
                                        @if ($setting->type == "text")
                                            <input type="text" class="form-control" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                        @elseif($setting->type == "text_area")
                                            <textarea class="form-control" name="{{ $setting->key }}">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "rich_text_box")
                                            <textarea class="form-control richTextBox" name="{{ $setting->key }}">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "markdown_editor")
                                            <textarea class="form-control easymde" name="{{ $setting->key }}">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "code_editor")
                                                <?php $options = json_decode($setting->details); ?>
                                            <div id="{{ $setting->key }}" data-theme="{{ @$options->theme }}" data-language="{{ @$options->language }}" class="ace_editor min_height_400" name="{{ $setting->key }}">{{ $setting->value ?? '' }}</div>
                                            <textarea name="{{ $setting->key }}" id="{{ $setting->key }}_textarea" class="hidden">{{ $setting->value ?? '' }}</textarea>
                                        @elseif($setting->type == "image" || $setting->type == "file")
                                            @if(isset( $setting->value ) && !empty( $setting->value ) && Storage::disk(config('hymer.storage.disk'))->exists($setting->value))
                                                <div class="img_settings_container">
                                                    <a href="{{ route('hymer.settings.delete_value', $setting->id) }}" class="hymer-x delete_value"></a>
                                                    <img src="{{ Storage::disk(config('hymer.storage.disk'))->url($setting->value) }}" style="width:200px; height:auto; padding:2px; border:1px solid #ddd; margin-bottom:10px;">
                                                </div>
                                                <div class="clearfix"></div>
                                            @elseif($setting->type == "file" && isset( $setting->value ))
                                                @if(json_decode($setting->value) !== null)
                                                    @foreach(json_decode($setting->value) as $file)
                                                        <div class="fileType">
                                                            <a class="fileType" target="_blank" href="{{ Storage::disk(config('hymer.storage.disk'))->url($file->download_link) }}">
                                                                {{ $file->original_name }}
                                                            </a>
                                                            <a href="{{ route('hymer.settings.delete_value', $setting->id) }}" class="hymer-x delete_value"></a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                            <input type="file" name="{{ $setting->key }}">
                                        @elseif($setting->type == "select_dropdown")
                                                <?php $options = json_decode($setting->details); ?>
                                                <?php $selected_value = (isset($setting->value) && !empty($setting->value)) ? $setting->value : NULL; ?>
                                            <select class="form-control" name="{{ $setting->key }}">
                                                    <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                                @if(isset($options->options))
                                                    @foreach($options->options as $index => $option)
                                                        <option value="{{ $index }}" @if($default == $index && $selected_value === NULL) selected="selected" @endif @if($selected_value == $index) selected="selected" @endif>{{ $option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        @elseif($setting->type == "radio_btn")
                                                <?php $options = json_decode($setting->details); ?>
                                                <?php $selected_value = (isset($setting->value) && !empty($setting->value)) ? $setting->value : NULL; ?>
                                                <?php $default = (isset($options->default)) ? $options->default : NULL; ?>
                                            <ul class="radio">
                                                @if(isset($options->options))
                                                    @foreach($options->options as $index => $option)
                                                        <li>
                                                            <input type="radio" id="option-{{ $index }}" name="{{ $setting->key }}"
                                                                   value="{{ $index }}" @if($default == $index && $selected_value === NULL) checked @endif @if($selected_value == $index) checked @endif>
                                                            <label for="option-{{ $index }}">{{ $option }}</label>
                                                            <div class="check"></div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @elseif($setting->type == "checkbox")
                                                <?php $options = json_decode($setting->details); ?>
                                                <?php $checked = (isset($setting->value) && $setting->value == 1) ? true : false; ?>
                                            @if (isset($options->on) && isset($options->off))
                                                <input type="checkbox" name="{{ $setting->key }}" class="form-check form-switch" @if($checked) checked @endif data-on="{{ $options->on }}" data-off="{{ $options->off }}">
                                            @else
                                                <input type="checkbox" name="{{ $setting->key }}" @if($checked) checked @endif class="form-check form-switch">
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-2 ">
                                        <select class="form-control group_select" name="{{ $setting->key }}_group">
                                            @foreach($groups as $group)
                                                <option value="{{ $group }}" {!! $setting->group == $group ? 'selected' : '' !!}>{{ $group }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-primary float-end">{{ __('hymer::settings.save') }}</button>
        </form>

        <div style="clear:both"></div>

        @can('add', Hymer::model('Setting'))
        <div class="panel" style="margin-top:10px;">
            <div class="panel-heading new-setting">
                <hr>
                <h3 class="panel-title"><i class="hymer-plus"></i> {{ __('hymer::settings.new') }}</h3>
            </div>
            <div class="panel-body">
                <form class=container-fluid action="{{ route('hymer.settings.store') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="setting_tab" class="setting_tab" value="{{ $active }}" />
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label" for="display_name">{{ __('hymer::generic.name') }}</label>
                            <input type="text" class="form-control" name="display_name" placeholder="{{ __('hymer::settings.help_name') }}" required="required">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="key">{{ __('hymer::generic.key') }}</label>
                            <input type="text" class="form-control" name="key" placeholder="{{ __('hymer::settings.help_key') }}" required="required">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="type">{{ __('hymer::generic.type') }}</label>
                            <select name="type" class="form-control" required="required">
                                <option value="text">{{ __('hymer::form.type_textbox') }}</option>
                                <option value="text_area">{{ __('hymer::form.type_textarea') }}</option>
                                <option value="rich_text_box">{{ __('hymer::form.type_richtextbox') }}</option>
                                <option value="markdown_editor">{{ __('hymer::form.type_markdowneditor') }}</option>
                                <option value="code_editor">{{ __('hymer::form.type_codeeditor') }}</option>
                                <option value="checkbox">{{ __('hymer::form.type_checkbox') }}</option>
                                <option value="radio_btn">{{ __('hymer::form.type_radiobutton') }}</option>
                                <option value="select_dropdown">{{ __('hymer::form.type_selectdropdown') }}</option>
                                <option value="file">{{ __('hymer::form.type_file') }}</option>
                                <option value="image">{{ __('hymer::form.type_image') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="group">{{ __('hymer::settings.group') }}</label>
                            <select class="form-control group_select group_select_new" name="group">
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <a id="toggle_options"><i class="hymer-double-down"></i> {{ mb_strtoupper(__('hymer::generic.options')) }}</a>
                            <div class="new-settings-options">
                                <label for="options">{{ __('hymer::generic.options') }}
                                    <small>{{ __('hymer::settings.help_option') }}</small>
                                </label>
                                <div id="options-editor" class="form-control ace-editor min_height_200" data-language="json"></div>
                                <textarea id="options-textarea" name="details" class="hidden"></textarea>
                                <div id="valid_options" class="alert-success alert" style="display:none">{{ __('hymer::json.valid') }}</div>
                                <div id="invalid_options" class="alert-danger alert" style="display:none">{{ __('hymer::json.invalid') }}</div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <button type="submit" class="btn btn-primary float-end new-setting-btn">
                            <i class="hymer-plus"></i> {{ __('hymer::settings.add_new') }}
                        </button>
                        <div style="clear:both"></div>
                    </div>
                </form>
            </div>
        </div>
        @endcan
    </div>

    @can('delete', Hymer::model('Setting'))
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="hymer-trash"></i> {!! __('hymer::settings.delete_question', ['setting' => '<span id="delete_setting_title"></span>']) !!}
                    </h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal" aria-label="{{ __('hymer::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default float-end"
                            data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger float-end delete-confirm" value="{{ __('hymer::settings.delete_confirm') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('#toggle_options').click(function () {
                $('.new-settings-options').toggle()
                if ($('#toggle_options .hymer-double-down').length) {
                    $('#toggle_options .hymer-double-down').removeClass('hymer-double-down').addClass('hymer-double-up')
                } else {
                    $('#toggle_options .hymer-double-up').removeClass('hymer-double-up').addClass('hymer-double-down')
                }
            })

            @can('delete', Hymer::model('Setting'))
            $('.panel-actions .hymer-trash').click(function () {
                var display = $(this).data('display-name') + '/' + $(this).data('display-key')

                $('#delete_setting_title').text(display)

                $('#delete_form')[0].action = '{{ route('hymer.settings.delete', [ 'id' => '__id' ]) }}'.replace('__id', $(this).data('id'))
                $('#delete_modal').modal('show')
            })
            @endcan



            $('[data-toggle="tab"]').click(function() {
                $(".setting_tab").val($(this).html())
            })

            $('.delete_value').click(function(e) {
                e.preventDefault()
                $(this).closest('form').attr('action', $(this).attr('href'))
                $(this).closest('form').submit()
            })

            // Initialize rich text editor
            tinymce.init(window.tinymceConfig)
        });
    </script>
    <script type="text/javascript">
    $(".group_select").not('.group_select_new').select2({
        tags: true,
        width: 'resolve'
    });
    $(".group_select_new").select2({
        tags: true,
        width: 'resolve',
        placeholder: '{{ __("hymer::generic.select_group") }}'
    });
    $(".group_select_new").val('').trigger('change');
    </script>
    <div style="display:none">
        <input type="hidden" id="upload_url" value="{{ route('hymer.upload') }}">
        <input type="hidden" id="upload_type_slug" value="settings">
    </div>

    <script>
        const optionsEditor = ace.edit('options-editor')
        const jsonMode = ace.require("ace/mode/json").Mode
        optionsEditor.session.setMode(new jsonMode())

        const optionsTextarea = document.getElementById('options-textarea')
        optionsEditor.session.on('change', function() {
            optionsTextarea.value = optionsEditor.getValue()
        })
    </script>
@stop
