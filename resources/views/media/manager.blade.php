@section('media-manager')
<div>
    <div v-if="hidden_element" :id="'dd_'+uid" class="dd">
        <ol id="files" class="dd-list">
            <li v-for="file in getSelectedFiles()" class="dd-item" :data-url="file">
                <div class="file_link selected" aria-hidden="true" data-bs-toggle="tooltip" data-placement="auto" :title="file">
                    <div class="link_icon">
                        <template v-if="fileIs(file, 'image')">
                            <div class="img_icon"
                                 :style="imgIcon('{{ Storage::disk(config('hymer.storage.disk'))->url('/') }}'+file)">
                            </div>
                        </template>
                        <template v-else-if="fileIs(file, 'video')">
                            <i class="icon hymer-video"></i>
                        </template>
                        <template v-else-if="fileIs(file, 'audio')">
                            <i class="icon hymer-music"></i>
                        </template>
                        <template v-else-if="fileIs(file, 'zip')">
                            <i class="icon hymer-archive"></i>
                        </template>
                        <template v-else-if="fileIs(file, 'folder')">
                            <i class="icon hymer-folder"></i>
                        </template>
                        <template v-else>
                            <i class="icon hymer-file-text"></i>
                        </template>
                    </div>
                    <div class="details">
                        <div class="folder">
                            <h4>@{{ getFileName(file) }}</h4>
                        </div>
                    </div>
                    <i class="hymer-x dd-nodrag" v-on:click="removeFileFromInput(file)"></i>
                </div>
            </li>
        </ol>
    </div>
    <div v-if="hidden_element">
        <div class="btn btn-sm btn-default" v-on:click="isExpanded = !isExpanded;" style="width:100%">
            <div v-if="!isExpanded"><i class="hymer-double-down"></i> {{ __('hymer::generic.open') }}</div>
            <div v-if="isExpanded"><i class="hymer-double-up"></i> {{ __('hymer::generic.close') }}</div>
        </div>
    </div>
    <div id="toolbar" v-if="showToolbar" :style="isExpanded ? 'display:block' : 'display:none'">
        <div class="btn-group offset-right">
            <button type="button" class="btn btn-primary" id="upload" v-if="allowUpload">
                <i class="hymer-upload"></i>
                {{ __('hymer::generic.upload') }}
            </button>
            <button type="button" class="btn btn-primary" v-if="allowCreateFolder" data-bs-toggle="modal" :data-bs-target="'#create_dir_modal_'+uid">
                <i class="hymer-folder"></i>
                {{ __('hymer::generic.add_folder') }}
            </button>
        </div>
        <button type="button" class="btn btn-default" v-on:click="getFiles()">
            <i class="hymer-refresh"></i>
        </button>
        <div class="btn-group offset-right">
            <button type="button" :disabled="selected_files.length === 0" v-if="allowUpload && hidden_element" class="btn btn-default" v-on:click="addSelectedFiles()">
                <i class="hymer-upload"></i>
                {{ __('hymer::media.add_all_selected') }}
            </button>
            <button type="button" v-if="showFolders && allowMove" class="btn btn-default" data-bs-toggle="modal" :data-bs-target="'#move_files_modal_'+uid">
                <i class="hymer-move"></i>
                {{ __('hymer::generic.move') }}
            </button>
            <button type="button" v-if="allowDelete" :disabled="selected_files.length == 0" class="btn btn-default" data-bs-toggle="modal" :data-bs-target="'#confirm_delete_modal_'+uid">
                <i class="hymer-trash"></i>
                {{ __('hymer::generic.delete') }}
            </button>
            <button v-if="allowCrop" :disabled="selected_files.length != 1 || !fileIs(selected_file, 'image')" type="button" class="btn btn-default" data-bs-toggle="modal" :data-bs-target="'#crop_modal_'+uid">
                <i class="hymer-crop"></i>
                {{ __('hymer::media.crop') }}
            </button>
        </div>
    </div>
    <div id="uploadPreview" style="display:none;" v-if="allowUpload"></div>
    <div id="uploadProgress" class="progress active progress-striped" v-if="allowUpload">
        <div class="progress-bar progress-bar-success" style="width: 0"></div>
    </div>
    <div id="content" :style="isExpanded ? 'display:block' : 'display:none'">
        <div class="breadcrumb-container">
            <ol class="breadcrumb file-manager align-items-center">
                <li class="media_breadcrumb" v-on:click="setCurrentPath(-1)">
                    <span class="arrow"></span>
                    <strong>{{ __('hymer::media.library') }}</strong>
                </li>
                <li v-for="(folder, i) in getCurrentPath()" v-on:click="setCurrentPath(i)">
                    <span class="arrow"></span>
                    @{{ folder }}
                </li>
            </ol>
        </div>
        <div class="flex">
            <div id="left">
                <ul id="files">
                    <li v-for="(file) in files" v-on:click="selectFile(file, $event)" v-on:dblclick="openFile(file)" v-if="filter(file)">
                        <div :class="'file_link ' + (isFileSelected(file) ? 'selected' : '')">
                            <div class="link_icon">
                                <template v-if="fileIs(file, 'image')">
                                    <div class="img_icon" :style="imgIcon(file.path)"></div>
                                </template>
                                <template v-else-if="fileIs(file, 'video')">
                                    <i class="icon hymer-video"></i>
                                </template>
                                <template v-else-if="fileIs(file, 'audio')">
                                    <i class="icon hymer-music"></i>
                                </template>
                                <template v-else-if="fileIs(file, 'zip')">
                                    <i class="icon hymer-archive"></i>
                                </template>
                                <template v-else-if="fileIs(file, 'folder')">
                                    <i class="icon hymer-folder"></i>
                                </template>
                                <template v-else>
                                    <i class="icon hymer-file-text"></i>
                                </template>
                            </div>
                            <div class="details">
                                <div :class="file.type">
                                    <h4>@{{ file.name }}</h4>
                                    <small v-if="!fileIs(file, 'folder')">
                                        <span class="file_size">@{{ bytesToSize(file.size) }}</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div id="file_loader" v-if="is_loading">
                    <?php $admin_loader_img = Hymer::setting('admin.loader', ''); ?>
                    @if($admin_loader_img == '')
                    <img src="{{ hymer_asset('images/logo-icon.png') }}" alt="Hymer Loader">
                    @else
                    <img src="{{ Hymer::image($admin_loader_img) }}" alt="Hymer Loader">
                    @endif
                    <p>{{ __('hymer::media.loading') }}</p>
                </div>

                <div id="no_files" v-if="files.length == 0">
                    <h3><i class="hymer-meh"></i> {{ __('hymer::media.no_files_in_folder') }}</h3>
                </div>
            </div>
            <div id="right">
                <div class="right_details">
                    <div v-if="selected_files.length > 1" class="right_none_selected">
                        <i class="hymer-list"></i>
                        <p>@{{ selected_files.length }} {{ __('hymer::media.files_selected') }}</p>
                    </div>
                    <div v-else-if="selected_files.length == 1" class="right_details">
                        <div class="detail_img">
                            <div v-if="fileIs(selected_file, 'image')">
                                <img :src="selected_file.path" />
                            </div>
                            <div v-else-if="fileIs(selected_file, 'video')">
                                <video width="100%" height="auto" ref="videoplayer" controls>
                                    <source :src="selected_file.path" type="video/mp4">
                                    <source :src="selected_file.path" type="video/ogg">
                                    <source :src="selected_file.path" type="video/webm">
                                    {{ __('hymer::media.browser_video_support') }}
                                </video>
                            </div>
                            <div v-else-if="fileIs(selected_file, 'audio')">
                                <i class="hymer-music"></i>
                                <audio controls style="width:100%; margin-top:5px;" ref="audioplayer">
                                    <source :src="selected_file.path" type="audio/ogg">
                                    <source :src="selected_file.path" type="audio/mpeg">
                                    {{ __('hymer::media.browser_audio_support') }}
                                </audio>
                            </div>
                            <div v-else-if="fileIs(selected_file, 'zip')">
                                <i class="hymer-archive"></i>
                            </div>
                            <div v-else-if="fileIs(selected_file, 'folder')">
                                <i class="hymer-folder"></i>
                            </div>
                            <div v-else>
                                <i class="hymer-file-text"></i>
                            </div>
                        </div>
                        <div class="detail_info">
                            <span>
                                <h4>{{ __('hymer::media.title') }}:</h4>
                                <input v-if="allowRename" type="text" class="form-control" :value="selected_file.name" @keydown.enter.prevent="renameFile">
                                <p v-else>@{{ selected_file.name }}</p>
                            </span>
                            <span>
                                <h4>{{ __('hymer::media.type') }}:</h4>
                                <p>@{{ selected_file.type }}</p>
                            </span>

                            <template v-if="!fileIs(selected_file, 'folder')">
                                <span>
                                    <h4>{{ __('hymer::media.size') }}:</h4>
                                    <p><span class="selected_file_size">@{{ bytesToSize(selected_file.size) }}</span></p>
                                </span>
                                <span>
                                    <h4>{{ __('hymer::media.public_url') }}:</h4>
                                    <p><a :href="selected_file.path" target="_blank">{{ __('hymer::generic.click_here') }}</a></p>
                                </span>
                                <span>
                                    <h4>{{ __('hymer::media.last_modified') }}:</h4>
                                    <p>@{{ dateFilter(selected_file.last_modified) }}</p>
                                </span>
                            </template>

                            <span v-if="fileIs(selected_file, 'image') && selected_file.thumbnails.length > 0">
                                <h4>Thumbnails</h4><br>
                                <ul>
                                    <li v-for="thumbnail in selected_file.thumbnails">
                                        <a :href="thumbnail.path" target="_blank">
                                            @{{ thumbnail.thumb_name }}
                                        </a>
                                    </li>
                                </ul>
                            </span>
                        </div>
                    </div>
                    <div v-else class="right_none_selected">
                        <i class="hymer-cursor"></i>
                        <p>{{ __('hymer::media.nothing_selected') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" :id="'image-modal_'+uid" v-if="selected_file && fileIs(selected_file, 'image')">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-end">
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <img :src="selected_file.path" class="img img-responsive" style="margin: 0 auto;">
                </div>

                <div class="modal-footer text-start">
                    <small class="image-title">@{{ selected_file.name }}</small>
                </div>

            </div>
        </div>
    </div>
    <!-- End Image Modal -->

    <!-- New Folder Modal -->
    <div class="modal fade modal-info" :id="'create_dir_modal_'+uid">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title"><i class="hymer-folder"></i> {{ __('hymer::media.add_new_folder') }}</h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <input name="new_folder_name"
                           placeholder="{{ __('hymer::media.new_folder_name') }}"
                           class="form-control"
                           v-model="modals.new_folder.name" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <button type="button" class="btn btn-info" v-on:click="createFolder">{{ __('hymer::media.create_new_folder') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End New Folder Modal -->

    <!-- Delete File Modal -->
    <div class="modal fade modal-danger" :id="'confirm_delete_modal_'+uid" v-if="allowDelete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title"><i class="hymer-warning"></i> {{ __('hymer::generic.are_you_sure') }}</h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <h4>{{ __('hymer::media.delete_question') }}</h4>
                    <ul>
                        <li v-for="file in selected_files">@{{ file.name }}</li>
                    </ul>
                    <h5 class="folder_warning">
                        <i class="hymer-warning"></i> {{ __('hymer::media.delete_folder_question') }}
                    </h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" v-on:click="deleteFiles">{{ __('hymer::generic.delete_confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->

    <!-- Move Files Modal -->
    <div class="modal fade modal-warning" :id="'move_files_modal_'+uid" v-if="allowMove">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title"><i class="hymer-move"></i> {{ __('hymer::media.move_file_folder') }}</h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal"
                            aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <h6>{{ __('hymer::media.destination_folder') }}</h6>
                    <select class="form-control" v-model="modals.move_files.destination">
                        <option value="" disabled>{{ __('hymer::media.destination_folder') }}</option>
                        <option v-if="current_folder != basePath && showFolders" value="/../">../</option>
                        <option v-for="file in files" v-if="file.type == 'folder' && !selected_files.includes(file)" :value="current_folder+'/'+file.name">@{{ file.name }}</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <button type="button" class="btn btn-warning" v-on:click="moveFiles">{{ __('hymer::generic.move') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Move File Modal -->

    <!-- Crop Image Modal -->
    <div class="modal fade modal-warning" :id="'crop_modal_'+uid" v-if="allowCrop">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">{{ __('hymer::media.crop_image') }}</h5>
                    <button type="button" class="btn modal-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="crop-container">
                        <img :id="'cropping-image_'+uid" v-if="selected_files.length == 1 && fileIs(selected_file, 'image')" class="img img-responsive" :src="selected_file.path + '?' + selected_file.last_modified" />
                    </div>
                    <div class="new-image-info">
                        {{ __('hymer::media.width') }} <span :id="'new-image-width_'+uid"></span>, {{ __('hymer::media.height') }}<span :id="'new-image-height_'+uid"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('hymer::generic.cancel') }}</button>
                    <button type="button" class="btn btn-warning" v-on:click="crop(false)">{{ __('hymer::media.crop') }}</button>
                    <button type="button" class="btn btn-warning" v-on:click="crop(true)">{{ __('hymer::media.crop_and_create') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Crop Image Modal -->
</div>
@endsection

<script>
    window.fileManager = Vue.createApp({})
    window.fileManager.component('media-manager', {
        template: `@yield('media-manager')`,
        props: {
            uid: {
                type: String,
                default: `${new Date().getTime()}`
            },
            basePath: {
                type: String,
                default: '/'
            },
            filename: {
                type: String,
                default: null
            },
            allowMultiSelect: {
                type: Boolean,
                default: true
            },
            maxSelectedFiles: {
                type: Number,
                default: 0
            },
            minSelectedFiles: {
                type: Number,
                default: 0
            },
            showFolders: {
                type: Boolean,
                default: true
            },
            showToolbar: {
                type: Boolean,
                default: true
            },
            allowUpload: {
                type: Boolean,
                default: true
            },
            allowMove: {
                type: Boolean,
                default: true
            },
            allowDelete: {
                type: Boolean,
                default: true
            },
            allowCreateFolder: {
                type: Boolean,
                default: true
            },
            allowRename: {
                type: Boolean,
                default: true
            },
            allowCrop: {
                type: Boolean,
                default: true
            },
            allowedTypes: {
                type: Array,
                default() {
                    return [];
                }
            },
            preSelect: {
                type: Boolean,
                default: true,
            },
            element: {
                type: String,
                default: ""
            },
            details: {
                type: Object,
                default: () => ({})
            },
            expanded: {
                type: Boolean,
                default: true,
            },
        },
        data() {
            return {
                file: '',
                current_folder: this.basePath,
		  		selected_files: [],
                files: [],
		  		is_loading: true,
                hidden_element: null,
                isExpanded: this.expanded,
                modals: {
                    new_folder: {
                        name: ''
                    },
                    move_files: {
                        destination: ''
                    }
                }
            };
        },
        computed: {
            selected_file() {
                return this.selected_files[0];
            }
        },
        methods: {
            getFiles() {
                var vm = this;
                vm.is_loading = true;
                $.post('{{ route('hymer.media.files') }}', { folder: vm.current_folder, _token: '{{ csrf_token() }}', details: vm.details }, function(data) {
                    vm.files = [];
                    for (var i = 0, file; file = data[i]; i++) {
                        if (vm.filter(file)) {
                            vm.files.push(file);
                        }
                    }
                    vm.selected_files = [];
                    if (vm.preSelect && data.length > 0) {
                        vm.selected_files.push(data[0]);
                    }
					vm.is_loading = false;
				});
            },
            selectFile(file, e) {
                if ((!e.ctrlKey && !e.metaKey && !e.shiftKey) || !this.allowMultiSelect) {
                    this.selected_files = [];
                }

                if (e.shiftKey && this.allowMultiSelect && this.selected_files.length == 1) {
                    var index = null;
                    var start = 0;
                    for (var i = 0, cfile; cfile = this.files[i]; i++) {
                        if (cfile === this.selected_file) {
                            start = i;
                            break;
                        }
                    }

                    var end = 0;
                    for (var i = 0, cfile; cfile = this.files[i]; i++) {
                        if (cfile === file) {
                            end = i;
                            break;
                        }
                    }

                    for (var i = start; i < end; i++) {
                        index = this.selected_files.indexOf(this.files[i]);
                        if (index === -1) {
                            this.selected_files.push(this.files[i]);
                        }
                    }
                }

                index = this.selected_files.indexOf(file);
                if (index === -1) {
                    this.selected_files.push(file);
                }

                if (this.selected_files.length == 1) {
                    var vm = this;
                    Vue.nextTick(function () {
                        if (vm.fileIs(vm.selected_file, 'video')) {
                            vm.$refs.videoplayer.load();
                        } else if (vm.fileIs(vm.selected_file, 'audio')) {
                            vm.$refs.audioplayer.load();
                        }
                    });
                }
            },
            openFile(file) {
                if (file.type == 'folder') {
                    this.current_folder += file.name+"/";
                    this.getFiles();
                } else if (this.hidden_element) {
                    this.addFileToInput(file);
                } else {
                    if (this.fileIs(this.selected_file, 'image')) {
                        $('#image-modal_' + this.uid).modal('show');
                    } else {
                        // ...
                    }
                }
            },
            isFileSelected(file) {
                return this.selected_files.includes(file);
            },
            fileIs(file, type) {
                if (typeof file === 'string') {
                    if (type == 'image') {
                        return this.endsWithAny(['jpg', 'jpeg', 'png', 'bmp'], file.toLowerCase());
                    }
                    //Todo: add other types
                } else {
                    return file.type.includes(type);
                }

                return false;
			},
            getCurrentPath() {
                var path = this.current_folder.replace(this.basePath, '').split('/').filter(function (el) {
                    return el != '';
                });

                return path;
            },
            setCurrentPath(i) {
                if (i == -1) {
                    this.current_folder = this.basePath;
                } else {
                    var path = this.getCurrentPath();
                    path.length = i + 1;
                    this.current_folder = this.basePath+path.join('/') + '/';
                }

                this.getFiles();
            },
            filter(file) {
                if (this.allowedTypes.length > 0) {
                    if (file.type != 'folder') {
                        for (var i = 0, type; type = this.allowedTypes[i]; i++) {
                            if (file.type.includes(type)) {
                                return true;
                            }
                        }
                    }
                }

                if (file.type == 'folder' && this.showFolders) {
                    return true;
                } else if (file.type == 'folder' && !this.showFolders) {
                    return false;
                }
                if (this.allowedTypes.length == 0) {
                    return true;
                }

                return false;
            },
            addFileToInput(file) {
                if (file.type != 'folder') {
                    if (!this.allowMultiSelect) {
                        this.hidden_element.value = file.relative_path;
                    } else {
                        var content = JSON.parse(this.hidden_element.value);
                        if (content.indexOf(file.relative_path) !== -1) {
                            return;
                        }
                        if (content.length >= this.maxSelectedFiles && this.maxSelectedFiles > 0) {
                            var msg_sing = "{{ trans_choice('hymer::media.max_files_select', 1) }}";
                            var msg_plur = "{{ trans_choice('hymer::media.max_files_select', 2) }}";
                            if (this.maxSelectedFiles == 1) {
                                toastr.error(msg_sing);
                            } else {
                                toastr.error(msg_plur.replace('2', this.maxSelectedFiles));
                            }
                        } else {
                            content.push(file.relative_path);
                            this.hidden_element.value = JSON.stringify(content);
                        }
                    }
                    this.$forceUpdate();
                }
            },
            removeFileFromInput(path) {
                if (this.allowMultiSelect) {
                    var content = JSON.parse(this.hidden_element.value);
                    if (content.indexOf(path) !== -1) {
                        content.splice(content.indexOf(path), 1);
                        this.hidden_element.value = JSON.stringify(content);
                        this.$forceUpdate();
                    }
                } else {
                    this.hidden_element.value = '';
                }
            },
            getSelectedFiles() {
                if (!this.allowMultiSelect) {
                    var content = [];
                    if (this.hidden_element.value != '') {
                        content.push(this.hidden_element.value);
                    }

                    return content;
                } else {
                    return JSON.parse(this.hidden_element.value);
                }
            },
            renameFile(object) {
                var vm = this;
                if (!this.allowRename || vm.selected_file.name == object.target.value) {
                    return;
                }
                $.post('{{ route('hymer.media.rename') }}', {
                    folder_location: vm.current_folder,
                    filename: vm.selected_file.name,
                    new_filename: object.target.value,
                    _token: '{{ csrf_token() }}'
                }, function(data){
					if (data.success == true) {
						toastr.success('{{ __('hymer::media.success_renamed') }}', "{{ __('hymer::generic.sweet_success') }}");
						vm.getFiles();
					} else {
						toastr.error(data.error, "{{ __('hymer::generic.whoopsie') }}");
					}
				});
            },
            createFolder(e) {
                if (!this.allowCreateFolder) {
                    return;
                }
                var vm = this;
                var name = this.modals.new_folder.name;
                $.post('{{ route('hymer.media.new_folder') }}', { new_folder: vm.current_folder+'/'+name, _token: '{{ csrf_token() }}' }, function(data) {
					if(data.success == true){
						toastr.success('{{ __('hymer::generic.successfully_created') }} ' + name, "{{ __('hymer::generic.sweet_success') }}");
						vm.getFiles();
					} else {
						toastr.error(data.error, "{{ __('hymer::generic.whoopsie') }}");
					}
                    vm.modals.new_folder.name = '';
					$('#create_dir_modal_'+vm.uid).modal('hide');
				});
            },
            deleteFiles() {
                if (!this.allowDelete) {
                    return;
                }
                var vm = this;
                $.post('{{ route('hymer.media.delete') }}', {
                    path: vm.current_folder,
                    files: vm.selected_files,
                    _token: '{{ csrf_token() }}'
                }, function(data){
					if(data.success == true){
						toastr.success('', "{{ __('hymer::generic.sweet_success') }}");
						vm.getFiles();
						$('#confirm_delete_modal_'+vm.uid).modal('hide');
					} else {
						toastr.error(data.error, "{{ __('hymer::generic.whoopsie') }}");
                        vm.getFiles();
						$('#confirm_delete_modal_'+vm.uid).modal('hide');
					}
				});
            },
            moveFiles(e) {
                if (!this.allowMove) {
                    return;
                }
                var vm = this;
                var destination = this.modals.move_files.destination;
                if (destination === '') {
                    return;
                }
                $('#move_files_modal_'+vm.uid).modal('hide');
				$.post('{{ route('hymer.media.move') }}', {
                    path: vm.current_folder,
                    files: vm.selected_files,
                    destination: destination,
                    _token: '{{ csrf_token() }}'
                }, function(data){
					if(data.success == true){
						toastr.success('{{ __('hymer::media.success_moved') }}', "{{ __('hymer::generic.sweet_success') }}");
						vm.getFiles();
					} else {
						toastr.error(data.error, "{{ __('hymer::generic.whoopsie') }}");
					}

                    vm.modals.move_files.destination = '';
				});
            },
            crop(mode) {
                if (!this.allowCrop) {
                    return;
                }
                if (!mode) {
                    if (!window.confirm('{{ __('hymer::media.crop_override_confirm') }}')) {
						return;
					}
                }

                croppedData.originImageName = this.selected_file.name;
				croppedData.upload_path = this.current_folder;
				croppedData.createMode = mode;

                const vm = this;
                const postData = Object.assign(croppedData, { _token: '{{ csrf_token() }}' });
				$.post('{{ route('hymer.media.crop') }}', postData, function(data) {
					if (data.success) {
						toastr.success(data.message);
						vm.getFiles();
						$('#crop_modal_'+vm.uid).modal('hide');
					} else {
						toastr.error(data.error, "{{ __('hymer::generic.whoopsie') }}");
					}
				});
            },
            addSelectedFiles () {
                var vm = this;
                for (i = 0; i < vm.selected_files.length; i++) {
                    vm.openFile(vm.selected_files[i]);
                }
            },
            bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			},
            getFileName(name) {
                var name = name.split('/');
                return name[name.length -1];
            },
            imgIcon(path) {
                path = path.replace(/\\/g,"/");
				return 'background-size: cover; background-image: url("' + path + '"); background-repeat:no-repeat; background-position:center center;display:inline-block; width:100%; height:100%;';
			},
            dateFilter(date) {
                if (!date) {
                    return null;
                }
                var date = new Date(date * 1000);

                var month = "0" + (date.getMonth() + 1);
                var minutes = "0" + date.getMinutes();
                var seconds = "0" + date.getSeconds();

                var dateFormated = date.getFullYear() + '-' + month.substr(-2) + '-' + date.getDate() + ' ' + date.getHours() + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

                return dateFormated;
            },
            endsWithAny(suffixes, string) {
                return suffixes.some(function (suffix) {
                    return string.endsWith(suffix);
                });
            }
        },
        mounted() {
            this.getFiles();
            var vm = this;

            if (this.element != '') {
                this.hidden_element = document.querySelector(this.element);
                if (!this.hidden_element) {
                    console.error('Element "'+this.element+'" could not be found.');
                } else {
                    if (this.maxSelectedFiles > 1 && this.hidden_element.value == '') {
                        this.hidden_element.value = '[]';
                    }
                }
            }

            //Key events
            this.onkeydown = function(evt) {
                evt = evt || window.event;
                if (evt.keyCode == 39) {
                    evt.preventDefault();
                    for (var i = 0, file; file = vm.files[i]; i++) {
                        if (file === vm.selected_file) {
                            i = i + 1; // increase i by one
                            i = i % vm.files.length;
                            vm.selectFile(vm.files[i], evt);
                            break;
                        }
                    }
                } else if (evt.keyCode == 37) {
                    evt.preventDefault();
                    for (var i = 0, file; file = vm.files[i]; i++) {
                        if (file === vm.selected_file) {
                            if (i === 0) {
                                i = vm.files.length;
                            }
                            i = i - 1;
                            vm.selectFile(vm.files[i], evt);
                            break;
                        }
                    }
                } else if (evt.keyCode == 13) {
                    evt.preventDefault();
                    if (evt.target.tagName != 'INPUT') {
                        vm.openFile(vm.selected_file, null);
                    }
                }
            };
            //Dropzone
            var dropzone = $(vm.$el).first().find('#upload').first();
            var progress = $(vm.$el).first().find('#uploadProgress').first();
            var progress_bar = $(vm.$el).first().find('#uploadProgress .progress-bar').first();
            if (this.allowUpload && !dropzone.hasClass('dz-clickable')) {
                dropzone.dropzone({
                    timeout: 180000,
                    url: '{{ route('hymer.media.upload') }}',
                    previewsContainer: "#uploadPreview",
                    totaluploadprogress(uploadProgress, totalBytes, totalBytesSent) {
                        progress_bar.css('width', uploadProgress + '%');
    					if (uploadProgress == 100) {
    						progress.delay(1500).slideUp(function(){
    							progress_bar.css('width', '0%');
    						});
    					}
                    },
                    processing(){
                        progress.fadeIn();
                    },
                    sending(file, xhr, formData) {
                        formData.append("_token", '{{ csrf_token() }}');
                        formData.append("upload_path", vm.current_folder);
                        formData.append("filename", vm.filename);
                        formData.append("details", JSON.stringify(vm.details));
                    },
                    success(e, res) {
                        if (res.success) {
                            toastr.success(res.message, "{{ __('hymer::generic.sweet_success') }}");
                        } else {
                            toastr.error(res.message, "{{ __('hymer::generic.whoopsie') }}");
                        }
                    },
                    error(e, res, xhr) {
                        toastr.error(res, "{{ __('hymer::generic.whoopsie') }}");
                    },
                    queuecomplete() {
                        vm.getFiles();
                    }
                });
            }

            // Cropper
            if (this.allowCrop) {
                let cropper = $(vm.$el).first().find('#crop_modal_'+vm.uid).first();
                cropper.on('shown.bs.modal', function (e) {
                    if (typeof cropper !== 'undefined' && cropper instanceof Cropper) {
    					cropper.destroy();
    				}
    				let croppingImage = document.getElementById('cropping-image_'+vm.uid);
    				cropper = new Cropper(croppingImage, {
    					crop(e) {
    						document.getElementById('new-image-width_'+vm.uid).innerText = Math.round(e.detail.width) + 'px';
    						document.getElementById('new-image-height_'+vm.uid).innerText = Math.round(e.detail.height) + 'px';
    						croppedData = {
    							x: Math.round(e.detail.x),
    							y: Math.round(e.detail.y),
    							height: Math.round(e.detail.height),
    							width: Math.round(e.detail.width)
    						};
    					}
    				});
    			});
            }

            $(document).ready(function () {
                $(".form-edit-add").submit(function (e) {
                    if (vm.hidden_element) {
                        if (vm.maxSelectedFiles > 1) {
                            var content = JSON.parse(vm.hidden_element.value);
                            if (content.length < vm.minSelectedFiles) {
                                e.preventDefault();
                                var msg_sing = "{{ trans_choice('hymer::media.min_files_select', 1) }}";
                                var msg_plur = "{{ trans_choice('hymer::media.min_files_select', 2) }}";
                                if (vm.minSelectedFiles == 1) {
                                    toastr.error(msg_sing);
                                } else {
                                    toastr.error(msg_plur.replace('2', vm.minSelectedFiles));
                                }
                            }
                        } else {
                            if (vm.minSelectedFiles > 0 && vm.hidden_element.value == '') {
                                e.preventDefault();
                                toastr.error("{{ trans_choice('hymer::media.min_files_select', 1) }}");
                            }
                        }
                    }
                });

                // Nestable
                $('#dd_'+vm.uid).nestable({
                    maxDepth: 1,
                    handleClass: 'file_link',
                    collapseBtnHTML: '',
                    expandBtnHTML: '',
                    callback(l, e) {
                        if (vm.allowMultiSelect) {
                            var new_content = [];
                            var object = $('#dd_'+vm.uid).nestable('serialize');
                            for (var key in object) {
                                new_content.push(object[key].url);
                            }
                            vm.hidden_element.value = JSON.stringify(new_content);
                        }
                    }
                });

                $('#create_dir_modal_' + vm.uid).on('hidden.bs.modal', function () {
                    vm.modals.new_folder.name = '';
                });

                $('#move_files_modal_' + vm.uid).on('hidden.bs.modal', function () {
                    vm.modals.move_files.destination = '';
                });
            });
        },
    });
</script>
<style>
.dd-placeholder {
    flex: 1;
    width: 100%;
    min-width: 200px;
    max-width: 250px;
}
</style>
