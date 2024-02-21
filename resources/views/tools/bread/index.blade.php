@extends('hymer::master')

@section('page_title', __('hymer::generic.viewing').' '.__('hymer::generic.bread'))

@section('page_header')
    <h1 class="page-title d-flex align-items-center">
        <i class="hymer-bread"></i>
        <span>{{ __('hymer::generic.bread') }}</span>
    </h1>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('hymer::alerts')
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped database-tables">
                    <thead>
                    <tr>
                        <th>{{ __('hymer::database.table_name') }}</th>
                        <th style="text-align:right">{{ __('hymer::bread.bread_crud_actions') }}</th>
                    </tr>
                    </thead>

                    @foreach($tables as $table)
                        @continue(in_array($table->name, config('hymer.database.tables.hidden', [])))
                        <tr>
                            <td>
                                <p class="name">
                                    <a href="{{ route('hymer.database.show', $table->prefix.$table->name) }}"
                                       data-name="{{ $table->prefix.$table->name }}" class="desctable">
                                        {{ $table->name }}
                                    </a>
                                    <i class="hymer-data"
                                       style="font-size:25px; position:absolute; margin-left:10px; margin-top:-3px;"></i>
                                </p>
                            </td>

                            <td class="actions text-end">
                                @if($table->dataTypeId)
                                    <a href="{{ route('hymer.' . $table->slug . '.index') }}"
                                       class="btn btn-warning btn-sm browse_bread" style="margin-right: 0;">
                                        <i class="hymer-plus"></i>
                                        <span>{{ __('hymer::generic.browse') }}</span>
                                    </a>
                                    <a href="{{ route('hymer.bread.edit', $table->name) }}"
                                       class="btn btn-primary btn-sm edit">
                                        <i class="hymer-edit"></i> {{ __('hymer::generic.edit') }}
                                    </a>
                                    <a href="#delete-bread" data-id="{{ $table->dataTypeId }}"
                                       data-name="{{ $table->name }}"
                                       class="btn btn-danger btn-sm delete">
                                        <i class="hymer-trash"></i>
                                        <span>{{ __('hymer::generic.delete') }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('hymer.bread.create', $table->name) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="hymer-plus"></i>
                                        <span>{{ __('hymer::bread.add_bread') }}</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_builder_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="hymer-trash"></i>
                        {!! __('hymer::bread.delete_bread_quest', ['table' => '<span id="delete_builder_name"></span>']) !!}
                    </h5>
                    <button type="button" class="btn modal-close"
                            data-bs-dismiss="modal" aria-label="{{ __('hymer::generic.close') }}">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn"
                            data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <form action="#" id="delete_builder_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('hymer::bread.delete_bread_conf') }}">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-info fade" tabindex="-1" id="table_info" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="hymer-data"></i> @{{ table.name }}</h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal"
                            aria-label="{{ __('hymer::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ __('hymer::database.field') }}</th>
                            <th>{{ __('hymer::database.type') }}</th>
                            <th>{{ __('hymer::database.null') }}</th>
                            <th>{{ __('hymer::database.key') }}</th>
                            <th>{{ __('hymer::database.default') }}</th>
                            <th>{{ __('hymer::database.extra') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in table.rows">
                            <td><strong>@{{ row.Field }}</strong></td>
                            <td>@{{ row.Type }}</td>
                            <td>@{{ row.Null }}</td>
                            <td>@{{ row.Key }}</td>
                            <td>@{{ row.Default }}</td>
                            <td>@{{ row.Extra }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn"
                            data-bs-dismiss="modal">{{ __('hymer::generic.close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('javascript')

    <script>

        let vm = null

        const App = {
            data() {
                return {
                    table: {
                        name: '',
                        rows: []
                    },
                }
            },
            created() {
                vm = this
            }
        }

        const app = Vue.createApp(App)
        app.mount('#table_info')

        $(function () {

            // Setup Delete BREAD
            //
            $('table .actions').on('click', '.delete', function (e) {
                id = $(this).data('id')
                name = $(this).data('name')

                $('#delete_builder_name').text(name)
                $('#delete_builder_form')[0].action = '{{ route('hymer.bread.delete', ['__id']) }}'.replace('__id', id)
                $('#delete_builder_modal').modal('show')
            })

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault()
                const href = $(this).attr('href')
                vm.table.name = $(this).data('name')
                vm.table.rows = []
                $.get(href, function (data) {
                    $.each(data, function (key, val) {
                        vm.table.rows.push({
                            Field: val.field,
                            Type: val.type,
                            Null: val.null,
                            Key: val.key,
                            Default: val.default,
                            Extra: val.extra
                        })
                    })
                    $('#table_info').modal('show')
                })
            })
        })
    </script>

@stop
