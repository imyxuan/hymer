@extends('hymer::master')

@section('page_title', __('hymer::generic.viewing').' '.__('hymer::generic.database'))

@section('page_header')
    <h1 class="page-title d-flex align-items-center">
        <i class="hymer-data"></i>
        {{ __('hymer::generic.database') }}
        <div class="ms-3">
            <a href="{{ route('hymer.database.create') }}" class="btn btn-success"><i class="hymer-plus"></i>
                {{ __('hymer::database.create_new_table') }}</a>
        </div>
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
                            <th style="text-align:right" colspan="2">{{ __('hymer::database.table_actions') }}</th>
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
                            </p>
                        </td>

                        <td>
                            <div class="bread_actions">
                            @if($table->dataTypeId)
                                <a href="{{ route('hymer.' . $table->slug . '.index') }}"
                                   class="btn btn-warning btn-sm browse_bread">
                                    <i class="hymer-plus"></i> {{ __('hymer::database.browse_bread') }}
                                </a>
                                <a href="{{ route('hymer.bread.edit', $table->name) }}"
                                   class="btn btn-outline-primary btn-sm edit">
                                   {{ __('hymer::bread.edit_bread') }}
                                </a>
                                <a data-id="{{ $table->dataTypeId }}" data-name="{{ $table->name }}"
                                     class="btn btn-danger btn-sm delete">
                                     {{ __('hymer::bread.delete_bread') }}
                                </a>
                            @else
                                <a href="{{ route('hymer.bread.create', $table->name) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="hymer-plus"></i> {{ __('hymer::bread.add_bread') }}
                                </a>
                            @endif
                            </div>
                        </td>

                        <td class="actions d-flex justify-content-end">
                            <a href="{{ route('hymer.database.show', $table->prefix.$table->name) }}"
                               data-name="{{ $table->name }}"
                               class="btn btn-sm btn-warning desctable" style="display:inline; margin-right:10px;">
                               <i class="hymer-eye"></i> {{ __('hymer::generic.view') }}
                            </a>
                            <a href="{{ route('hymer.database.edit', $table->prefix.$table->name) }}"
                               class="btn btn-sm btn-primary" style="display:inline; margin-right:10px;">
                                <i class="hymer-edit"></i> {{ __('hymer::generic.edit') }}
                            </a>
                            <a class="btn btn-danger btn-sm delete_table @if($table->dataTypeId) remove-bread-warning @endif"
                               data-table="{{ $table->prefix.$table->name }}">
                                <i class="hymer-trash"></i> {{ __('hymer::generic.delete') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- Delete BREAD Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_bread_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="hymer-trash"></i>
                        {!! __('hymer::bread.delete_bread_quest', ['table' => '<span id="delete_bread_name"></span>']) !!}
                    </h5>
                    <button type="button" class="btn modal-close"
                            data-bs-dismiss="modal" aria-label="{{ __('hymer::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn"
                            data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <form action="#" id="delete_bread_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ __('hymer::bread.delete_bread_conf') }}">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="hymer-trash"></i>
                        {!! __('hymer::database.delete_table_question', ['table' => '<span id="delete_table_name"></span>']) !!}
                    </h5>
                    <button type="button" class="btn modal-close"
                            data-bs-dismiss="modal" aria-label="{{ __('hymer::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline float-end" style="margin-right:10px;"
                            data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}
                    </button>
                    <form action="#" id="delete_table_form" method="POST">
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit"
                               class="btn btn-danger"
                               value="{{ __('hymer::database.delete_table_confirm') }}">
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
                    <button type="button" class="btn" data-bs-dismiss="modal">{{ __('hymer::generic.close') }}</button>
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
            },
        }

        const app = Vue.createApp(App)
        app.mount('#table_info')

        $(function () {

            // Setup Show Table Info
            //
            $('.database-tables').on('click', '.desctable', function (e) {
                e.preventDefault()
                href = $(this).attr('href')
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
                    console.log(vm.table.rows)
                    $('#table_info').modal('show')
                })
            })

            // Setup Delete Table
            //
            $('td.actions').on('click', '.delete_table', function (e) {
                table = $(this).data('table');
                if ($(this).hasClass('remove-bread-warning')) {
                    toastr.warning('{{ __('hymer::database.delete_bread_before_table') }}');
                } else {
                    $('#delete_table_name').text(table);

                    $('#delete_table_form')[0].action = '{{ route('hymer.database.destroy', ['database' => '__database']) }}'.replace('__database', table)
                    $('#delete_modal').modal('show');
                }
            });

            // Setup Delete BREAD
            //
            $('table .bread_actions').on('click', '.delete', function (e) {
                id = $(this).data('id');
                name = $(this).data('name');

                $('#delete_bread_name').text(name);
                $('#delete_bread_form')[0].action = '{{ route('hymer.bread.delete', '__id') }}'.replace('__id', id);
                $('#delete_bread_modal').modal('show');
            });
        });
    </script>

@stop
