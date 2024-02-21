@extends('hymer::master')

@section('page_title', __('hymer::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title d-flex align-items-center">
        <i class="{{ $dataType->icon }}"></i>
        <span>{{ __('hymer::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')) }}</span>
        <span>{{ $dataType->getTranslatedAttribute('display_name_singular') }}</span>
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('hymer.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('hymer.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                    {{-- <div class="panel"> --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('hymer::generic.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('hymer::generic.name') }}"
                                       value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('hymer::generic.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('hymer::generic.email') }}"
                                       value="{{ old('email', $dataTypeContent->email ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('hymer::generic.password') }}</label>
                                @if(isset($dataTypeContent->password))
                                    <small class="form-tips fst-italic">{{ __('hymer::profile.password_hint') }}</small>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password">
                            </div>

                            @can('editRoles', $dataTypeContent)
                                <div class="mb-3">
                                    <label class="form-label" for="default_role">{{ __('hymer::profile.role_default') }}</label>
                                    @php
                                        $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};

                                        $row     = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('hymer::formfields.relationship')
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="additional_roles">{{ __('hymer::profile.roles_additional') }}</label>
                                    @php
                                        $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('hymer::formfields.relationship')
                                </div>
                            @endcan
                            @php
                            if (isset($dataTypeContent->locale)) {
                                $selected_locale = $dataTypeContent->locale;
                            } else {
                                $selected_locale = config('app.locale', 'en');
                            }

                            @endphp
                            <div class="mb-3">
                                <label class="form-label" for="locale">{{ __('hymer::generic.locale') }}</label>
                                <select class="form-control select2" id="locale" name="locale">
                                    @foreach (Hymer::getLocales() as $locale)
                                    <option value="{{ $locale }}"
                                    {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="mb-3">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Hymer::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary save">
                {{ __('hymer::generic.save') }}
            </button>
        </form>
        <div style="display:none">
            <input type="hidden" id="upload_url" value="{{ route('hymer.upload') }}">
            <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {

        });
    </script>
@stop
