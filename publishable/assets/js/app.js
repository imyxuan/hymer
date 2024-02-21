window.jQuery = jQuery;
window.$ = jQuery;

window.tinymceConfig = {
    menubar: false,
    selector: 'textarea.richTextBox',
    base_url: $('meta[name="assets-path"]').attr('content')+'?path=libs/tinymce-6.8.3',
    skin: 'oxide',
    min_height: 600,
    resize: true,
    plugins: 'link image code table lists',
    extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
    relative_urls: false, // Necessary so uploaded images don't get a relative path but an URL instead.
    remove_script_host: false,
    file_picker_types: 'image',
    file_picker_callback: (callback, value, meta) => {
        if (meta.filetype == 'image') {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function () {
                var formdata = new FormData();
                formdata.append('image', this.files[0]);
                formdata.append('type_slug', $('#upload_type_slug').val());
                // Show loader
                $('#hymer-loader').css('z-index', 10000);
                $('#hymer-loader').fadeIn();
                $.ajax({
                    type: 'post',
                    url: $('#upload_url').val(),
                    data: formdata,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                })
                    .done((result) => {
                        callback(result);
                    })
                    .always(() => {
                        $('#hymer-loader').fadeOut();
                        $('#hymer-loader').css('z-index', 99);
                    });
            }

            input.click();
        }
    },
    toolbar: 'styleselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
    image_caption: true,
    image_title: true,
    init_instance_callback: function (editor) {
        if (typeof tinymce_init_callback !== "undefined") {
            tinymce_init_callback(editor);
        }
    },
    setup: function (editor) {
        if (typeof tinymce_setup_callback !== "undefined") {
            tinymce_setup_callback(editor);
        }
    }
}


$(document).ready(function () {
    var appContainer = $(".app-container"),
        fadedOverlay = $('.fadetoblack'),
        hamburger = $('.hamburger');

    new PerfectScrollbar('.side-menu');

    $('#hymer-loader').fadeOut();

    $(".hamburger, .navbar-expand-toggle").on('click', function () {
        appContainer.toggleClass("expanded");
        $(this).toggleClass('is-active');
        if ($(this).hasClass('is-active')) {
            window.localStorage.setItem('hymer.stickySidebar', true);
        } else {
            window.localStorage.setItem('hymer.stickySidebar', false);
        }
    });

    let $dropdownParent = $(document.body)
    const $select2 = $('select.select2')
    if ($select2.parents('.modal').length !== 0) {
        $dropdownParent = $select2.parents('.modal')
    }
    $select2.select2({
        dropdownParent: $dropdownParent,
        width: '100%'
    });
    $('select.select2-ajax').each(function() {
        $(this).select2({
            width: '100%',
            tags: $(this).hasClass('taggable'),
            createTag: function(params) {
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                }
            },
            ajax: {
                url: $(this).data('get-items-route'),
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: $(this).data('get-items-field'),
                        method: $(this).data('method'),
                        id: $(this).data('id'),
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(this).on('select2:select',function(e){
            var data = e.params.data;
            if (data.id == '') {
                // "None" was selected. Clear all selected options
                $(this).val([]).trigger('change');
            } else {
                $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected','selected');
            }
        });

        $(this).on('select2:unselect',function(e){
            var data = e.params.data;
            $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected',false);
        });

        $(this).on('select2:selecting', function(e) {
            if (!$(this).hasClass('taggable')) {
                return;
            }
            var $el = $(this);
            var route = $el.data('route');
            var label = $el.data('label');
            var errorMessage = $el.data('error-message');
            var newTag = e.params.args.data.newTag;

            if (!newTag) return;

            $el.select2('close');

            $.post(route, {
                [label]: e.params.args.data.text,
                _tagging: true,
            }).done(function(data) {
                var newOption = new Option(e.params.args.data.text, data.data.id, false, true);
                $el.append(newOption).trigger('change');
            }).fail(function(error) {
                toastr.error(errorMessage);
            });

            return false;
        });
    });

    $('.match-height').matchHeight();

    new DataTable('.datatable', {})

    const $datepickerItems = $('.datepicker')
    if ($datepickerItems.length > 0) {
        for (let i = 0; i < $datepickerItems.length; i++) {
            new window.tempusDominus.TempusDominus($datepickerItems[i], {})
        }
    }

    // Save shortcut
    $(document).keydown(function (e) {
        if ((e.metaKey || e.ctrlKey) && e.keyCode == 83) { /*ctrl+s or command+s*/
            $(".btn.save").click();
            e.preventDefault();
            return false;
        }
    });

    /********** MARKDOWN EDITOR **********/

    $('textarea.easymde').each(function () {
        var easymde = new EasyMDE({
            element: this
        });
        easymde.render();
    });

    /********** END MARKDOWN EDITOR **********/

});
