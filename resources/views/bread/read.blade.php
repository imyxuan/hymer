@extends('hymer::master')

@section('page_title', __('hymer::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title d-flex align-items-center">
        <i class="{{ $dataType->icon }} d-flex align-items-center"></i>
        <span>{{ __('hymer::generic.viewing') }}</span>
        <span>{{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }}</span>

        <div class="d-flex align-items-center ms-3">
            @can('edit', $dataTypeContent)
                <a href="{{ route('hymer.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}"
                   class="btn btn-primary d-flex gap-2">
                    <i class="hymer-edit"></i>
                    <span class="d-none d-sm-none d-md-block">
                    {{ __('hymer::generic.edit') }}
                </span>
                </a>
            @endcan
            @can('delete', $dataTypeContent)
                @if($isSoftDeleted)
                    <a href="{{ route('hymer.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}"
                       title="{{ __('hymer::generic.restore') }}"
                       class="btn btn-default restore d-flex gap-2" data-id="{{ $dataTypeContent->getKey() }}"
                       id="restore-{{ $dataTypeContent->getKey() }}">
                        <i class="hymer-trash"></i>
                        <span class="d-none d-sm-none d-md-block">{{ __('hymer::generic.restore') }}</span>
                    </a>
                @else
                    <a href="javascript:;" title="{{ __('hymer::generic.delete') }}"
                       class="btn btn-danger delete d-flex gap-2" data-id="{{ $dataTypeContent->getKey() }}"
                       id="delete-{{ $dataTypeContent->getKey() }}">
                        <i class="hymer-trash"></i> <span class="d-none d-sm-none d-md-block">
                        {{ __('hymer::generic.delete') }}
                    </span>
                    </a>
                @endif
            @endcan
            @can('browse', $dataTypeContent)
                <a href="{{ route('hymer.'.$dataType->slug.'.index') }}" class="btn btn-warning to-list d-flex gap-2">
                    <i class="bi bi-list-ul"></i>
                    <span class="d-none d-sm-none d-md-block">
                {{ __('hymer::generic.return_to_list') }}
            </span>
                </a>
            @endcan
        </div>
    </h1>
    @include('hymer::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- form start -->
                    @foreach($dataType->readRows as $row)
                        @php
                        if ($dataTypeContent->{$row->field.'_read'}) {
                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                        }
                        @endphp
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                {{ $row->getTranslatedAttribute('display_name') }}
                            </h3>
                        </div>

                        <div class="panel-body">
                            @if (isset($row->details->view_read))
                                @include($row->details->view_read, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'read', 'options' => $row->details])
                            @elseif (isset($row->details->view))
                                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => 'read', 'view' => 'read', 'options' => $row->details])
                            @elseif($row->type == "image")
                                <img class="img-responsive"
                                     src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Hymer::image($dataTypeContent->{$row->field}) }}">
                            @elseif($row->type == 'multiple_images')
                                @if(json_decode($dataTypeContent->{$row->field}))
                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                        <img class="img-responsive"
                                             src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Hymer::image($file) }}">
                                    @endforeach
                                @else
                                    <img class="img-responsive"
                                         src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Hymer::image($dataTypeContent->{$row->field}) }}">
                                @endif
                            @elseif($row->type == 'relationship')
                                 @include('hymer::formfields.relationship', ['view' => 'read', 'options' => $row->details])
                            @elseif($row->type == 'markdown_editor')
                                 @include('hymer::formfields.markdown_editor', ['view' => 'read', 'options' => $row->details])
                            @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options') &&
                                    !empty($row->details->options->{$dataTypeContent->{$row->field}})
                            )
                                <?php echo $row->details->options->{$dataTypeContent->{$row->field}};?>
                            @elseif($row->type == 'select_multiple')
                                @if(property_exists($row->details, 'relationship'))

                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                        {{ $item->{$row->field}  }}
                                    @endforeach

                                @elseif(property_exists($row->details, 'options'))
                                    @if (!empty(json_decode($dataTypeContent->{$row->field})))
                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                            @if (@$row->details->options->{$item})
                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                            @endif
                                        @endforeach
                                    @else
                                        {{ __('hymer::generic.none') }}
                                    @endif
                                @endif
                            @elseif($row->type == 'date' || $row->type == 'timestamp')
                                @if ( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                    <p>
                                        {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
                                    </p>
                                @else
                                    <p>{{ $dataTypeContent->{$row->field} }}</p>
                                @endif
                            @elseif($row->type == 'checkbox')
                                @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                    @if($dataTypeContent->{$row->field})
                                    <span class="label label-info">{{ $row->details->on }}</span>
                                    @else
                                    <span class="label label-primary">{{ $row->details->off }}</span>
                                    @endif
                                @else
                                {{ $dataTypeContent->{$row->field} }}
                                @endif
                            @elseif($row->type == 'multiple_checkbox')
                                @foreach(json_decode($dataTypeContent->{$row->field}) as $key => $val)
                                    {{ $val }}
                                @endforeach
                            @elseif($row->type == 'color')
                                <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                            @elseif($row->type == 'coordinates')
                                @include('hymer::partials.coordinates')
                            @elseif($row->type == 'rich_text_box')
                                @include('hymer::multilingual.input-hidden-bread-read')
                                {!! $dataTypeContent->{$row->field} !!}
                            @elseif($row->type == 'file')
                                @if(json_decode($dataTypeContent->{$row->field}))
                                    @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                                        <a href="{{ Storage::disk(config('hymer.storage.disk'))->url($file->download_link) ?: '' }}">
                                            {{ $file->original_name ?: '' }}
                                        </a>
                                        <br/>
                                    @endforeach
                                @elseif($dataTypeContent->{$row->field})
                                    <a href="{{ Storage::disk(config('hymer.storage.disk'))->url($row->field) ?: '' }}">
                                        {{ __('hymer::generic.download') }}
                                    </a>
                                @endif
                            @else
                                @include('hymer::multilingual.input-hidden-bread-read')
                                <p>{{ $dataTypeContent->{$row->field} }}</p>
                            @endif
                        </div><!-- panel-body -->
                        @if(!$loop->last)
                            <div class="divider"></div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="hymer-trash"></i>
                        {{ __('hymer::generic.delete_question') }}
                    </h5>
                    <a role="button"
                       class="btn modal-close" data-bs-dismiss="modal"
                       aria-label="{{ __('hymer::generic.close') }}">
                        <span>&times;</span>
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
                        {{ __('hymer::generic.cancel') }}
                    </button>
                    <form action="{{ route('hymer.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger delete-confirm"
                               value="{{ __('hymer::generic.delete_confirm') }}">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function () {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function (e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/)
                ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                : deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });

    </script>
@stop
