@extends('hymer::master')

@section('css')

    @include('hymer::compass.includes.styles')

@stop

@section('page_header')
    <div class="page-title d-flex align-items-center">
        <i class="hymer-compass"></i>
        <div class="d-flex flex-column justify-content-center">
            <p class="mb-2"> {{ __('hymer::generic.compass') }} </p>
            <span class="page-description">{{ __('hymer::compass.welcome') }}</span>
        </div>
    </div>
@stop

@section('content')

    <div id="gradient_bg"></div>

    <div class="container-fluid">
        @include('hymer::alerts')
    </div>

    <div class="page-content compass container-fluid">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if(empty($active_tab) || $active_tab == 'resources') active @endif"
                   data-bs-toggle="tab" href="#resources">
                    <i class="hymer-book"></i> {{ __('hymer::compass.resources.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($active_tab == 'commands') active @endif" data-bs-toggle="tab" href="#commands">
                    <i class="hymer-terminal"></i> {{ __('hymer::compass.commands.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($active_tab == 'logs') active @endif" data-bs-toggle="tab" href="#logs">
                    <i class="hymer-logbook"></i> {{ __('hymer::compass.logs.title') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="resources"
                 class="tab-pane fade @if(empty($active_tab) || $active_tab == 'resources') show active @endif">
                <h3 class="d-flex align-items-center">
                    <i class="hymer-book"></i>
                    {{ __('hymer::compass.resources.title') }}
                    <small>{{ __('hymer::compass.resources.text') }}</small>
                </h3>

                <div class="accordion mt-3">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button" type="button"
                                data-bs-toggle="collapse" data-bs-target="#links"
                                aria-expanded="true" aria-controls="links">
                                <span class="fs-4">{{ __('hymer::compass.links.title') }}</span>
                            </button>
                        </h2>
                        <div class="accordion-collapse collapse show p-3" id="links">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="https://www.imyxuan.site/" target="_blank" class="hymer-link"
                                       style="background-image:url('{{ hymer_asset('images/compass/documentation.jpg') }}')">
                                    <span class="resource_label">
                                        <i class="hymer-documentation"></i>
                                        <span class="copy">{{ __('hymer::compass.links.documentation') }}</span>
                                    </span>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="https://www.imyxuan.site/" target="_blank" class="hymer-link"
                                       style="background-image:url('{{ hymer_asset('images/compass/hymer-home.jpg') }}')">
                                    <span class="resource_label">
                                        <i class="hymer-browser"></i>
                                        <span class="copy">{{ __('hymer::compass.links.hymer_homepage') }}</span>
                                    </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion mt-3">
                    <h2 class="accordion-header">
                        <button
                            class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#fonts"
                            aria-expanded="true" aria-controls="fonts">
                            <span class="fs-4">{{ __('hymer::compass.fonts.title') }}</span>
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse show p-3" id="fonts">
                        @include('hymer::compass.includes.fonts')
                    </div>

                </div>
            </div>

            <div id="commands" class="tab-pane fade @if($active_tab == 'commands') show active @endif">
                <h3>
                    <i class="hymer-terminal"></i>
                    {{ __('hymer::compass.commands.title') }}
                    <small>{{ __('hymer::compass.commands.text') }}</small>
                </h3>
                <div id="command_lists">
                    @include('hymer::compass.includes.commands')
                </div>

            </div>
            <div id="logs" class="tab-pane fade @if($active_tab == 'logs') show active @endif">
                <div class="row">

                    @include('hymer::compass.includes.logs')

                </div>
            </div>
        </div>

    </div>

@stop
@section('javascript')
    <!-- JS for commands -->
    <script>

        $(document).ready(function () {
            $('.command').click(function () {
                $(this).find('.cmd_form').slideDown()
                $(this).addClass('more_args')
                $(this).find('input[type="text"]').focus()
            })

            $('.close-output').click(function () {
                $('#commands pre').slideUp()
            })
        })

    </script>

    <!-- JS for logs -->
    <script>
        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle()
            })
            $('#table-log').DataTable({
                "order": [1, 'desc'],
                "stateSave": true,
                "language": {!! json_encode(__('hymer::datatable')) !!},
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data))
                },
                "stateLoadCallback": function (settings) {
                    const data = JSON.parse(window.localStorage.getItem("datatable"))
                    if (data) data.start = 0
                    return data
                }
            })

            $('#delete-log, #delete-all-log').click(function () {
                return confirm('{{ __('hymer::generic.are_you_sure') }}')
            })
        })
    </script>
@stop
