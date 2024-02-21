@extends('hymer::master')

@section('page_title', __('hymer::generic.media'))

@section('content')
    <div class="page-content container-fluid">
        @include('hymer::alerts')
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="page-title d-flex align-items-center"><i class="hymer-images"></i> {{ __('hymer::generic.media') }}</h3>
                </div>
                <div class="clear"></div>
                <div id="file-manager">
                    <media-manager
                        base-path="{{ config('hymer.media.path', '/') }}"
                        :show-folders="{{ config('hymer.media.show_folders', true) ? 'true' : 'false' }}"
                        :allow-upload="{{ config('hymer.media.allow_upload', true) ? 'true' : 'false' }}"
                        :allow-move="{{ config('hymer.media.allow_move', true) ? 'true' : 'false' }}"
                        :allow-delete="{{ config('hymer.media.allow_delete', true) ? 'true' : 'false' }}"
                        :allow-create-folder="{{ config('hymer.media.allow_create_folder', true) ? 'true' : 'false' }}"
                        :allow-rename="{{ config('hymer.media.allow_rename', true) ? 'true' : 'false' }}"
                        :allow-crop="{{ config('hymer.media.allow_crop', true) ? 'true' : 'false' }}"
                        :details="{{ json_encode(['thumbnails' => config('hymer.media.thumbnails', []), 'watermark' => config('hymer.media.watermark', (object)[])]) }}"
                        ></media-manager>
                </div>
            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->
@stop

@section('javascript')
<script>
    window.fileManager.mount('#file-manager')
</script>
@endsection
