/**
 * WP File Download
 *
 * @package WP File Download
 * @author Joomunited
 * @version 1.0
 */


jQuery(document).ready(function ($) {
    if (typeof(Wpfd) === 'undefined') {
        Wpfd = {};
    }

    _wpfd_text = function (text) {
        if (typeof(l10n) !== 'undefined') {
            return l10n[text];
        }
        return text;
    };

    //initUploadBtn();

    function toMB(mb) {
        return mb * 1024 * 1024;
    }

    var allowedExt = wpfd_admin.allowed;
    allowedExt = allowedExt.split(',');
    allowedExt.sort();
    var currentContainer = $('.file-upload-content');
    // Init the uploader
    var uploader = new Resumable({
        target: wpfd_var.wpfdajaxurl + '?action=wpfd&task=files.upload',
        query: {
            id_category: $('input[name=id_category]').val()
        },
        fileParameterName: 'file_upload',
        simultaneousUploads: 2,
        maxFileSize: toMB(wpfd_admin.maxFileSize),
        maxFileSizeErrorCallback: function (file) {
            bootbox.alert(file.name + ' ' + _wpfd_text('is too large') + '!');
        },
        chunkSize: wpfd_admin.serverUploadLimit - 50 * 1024, // Reduce 50KB to avoid error
        forceChunkSize: true,
        fileType: allowedExt,
        fileTypeErrorCallback: function (file) {
            bootbox.alert(file.name + ' cannot upload!<br/><br/>' + _wpfd_text('This type of file is not allowed to be uploaded. You can add new file types in the plugin configuration'));
        },
        generateUniqueIdentifier: function (file, event) {
            var relativePath = file.webkitRelativePath || file.fileName || file.name;
            var size = file.size;
            var prefix = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            return (prefix + size + '-' + relativePath.replace(/[^0-9a-zA-Z_-]/img, ''));
        }
    });

    if (!uploader.support) {
        bootbox.alert(_wpfd_text('Your browser does not support HTML5 file uploads!'));
    }

    if (typeof (willUpload) === 'undefined') {
        var willUpload = true;
    }

    uploader.on('filesAdded', function (files) {
        if (!wpfd_permissions.can_edit_category) {
            bootbox.alert(wpfd_permissions.translate.wpfd_edit_category);
            return false;
        }

        files.forEach(function (file) {
            var progressBlock = '<div class="wpfd_process_block" id="' + file.uniqueIdentifier + '">'
                + '<div class="wpfd_process_fileinfo">'
                + '<span class="wpfd_process_filename">' + file.fileName + '</span>'
                + '<span class="wpfd_process_cancel">Cancel</span>'
                + '</div>'
                + '<div class="wpfd_process_full" style="display: block;">'
                + '<div class="wpfd_process_run" data-w="0" style="width: 0%;"></div>'
                + '</div></div>';

            //$('#preview', '.wpreview').before(progressBlock);
            currentContainer.find('#preview', '.wpreview').before(progressBlock);
            $('.wpfd_process_cancel').unbind('click').click(function () {
                fileID = $(this).parents('.wpfd_process_block').attr('id');
                fileObj = uploader.getFromUniqueIdentifier(fileID);
                uploader.removeFile(fileObj);
                $(this).parents('.wpfd_process_block').fadeOut('normal', function () {
                    $(this).remove();
                });

                if (uploader.files.length === 0) {
                    $('.wpfd_process_pause').fadeOut('normal', function () {
                        $(this).remove();
                    });
                }

                $.ajax({
                    url: wpfd_var.wpfdajaxurl + '?action=wpfd&task=files.upload',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id_category: $('input[name=id_category]').val(),
                        deleteChunks: fileID
                    },
                    success: function (res, stt) {
                        if (res.response === true) {

                        }
                    }
                })
            });
        });

        // Do not run uploader if no files added or upload same files again
        if (files.length > 0) {
            uploadPauseBtn = $('.file-upload-content').find('.wpreview').find('.wpfd_process_pause').length;
            restableBlock = $('.file-upload-content').find('.wpfd_process_block');

            if (!uploadPauseBtn) {
                restableBlock.before('<div class="wpfd_process_pause">Pause</div>');
                $('.wpfd_process_pause').unbind('click').click(function () {
                    if (uploader.isUploading()) {
                        uploader.pause();
                        $(this).text('Start');
                        $(this).addClass('paused');
                        willUpload = false;
                    } else {
                        uploader.upload();
                        $(this).text('Pause');
                        $(this).removeClass('paused');
                        willUpload = true;
                    }
                });
            }

            uploader.opts.query = {
                id_category: currentContainer.find('input[name=id_category]').val()
            };

            if (willUpload) uploader.upload();
        }
    });

    uploader.on('fileProgress', function (file) {
        $('.wpfd_process_block#' + file.uniqueIdentifier)
            .find('.wpfd_process_run').width(Math.floor(file.progress() * 100) + '%');
    });

    uploader.on('fileSuccess', function (file, res) {
        thisUploadBlock = currentContainer.find('.wpfd_process_block#' + file.uniqueIdentifier);
        thisUploadBlock.find('.wpfd_process_cancel').addClass('uploadDone').text('OK').unbind('click');
        thisUploadBlock.find('.wpfd_process_full').remove();

        var response = JSON.parse(res);
        if (response.response === false && typeof(response.datas) !== 'undefined') {
            if (typeof(response.datas.code) !== 'undefined' && response.datas.code > 20) {
                bootbox.alert('<div>' + response.datas.message + '</div>');
                return false;
            }
        }
        if (typeof(response) === 'string') {
            bootbox.alert('<div>' + response + '</div>');
            return false;
        }

        if (response.response !== true) {
            bootbox.alert(response.response);
            return false;
        }
    });

    uploader.on('fileError', function (file, msg) {
        thisUploadBlock = currentContainer.find('.wpfd_process_block#' + file.uniqueIdentifier);
        thisUploadBlock.find('.wpfd_process_cancel').addClass('uploadError').text('Error').unbind('click');
        thisUploadBlock.find('.wpfd_process_full').remove();
    });

    uploader.on('complete', function () {
        currentContainer.find('.progress').delay(300).fadeIn(300).hide(300, function () {
            $(this).remove();
        });
        currentContainer.find('.uploaded').delay(300).fadeIn(300).hide(300, function () {
            $(this).remove();
        });
        $('#wpreview .file').delay(1200).show(1200, function () {
            $(this).removeClass('done placeholder');
        });

        $('.gritter-item-wrapper ').remove();
        var message = $('<div class="upload_message" style="width: 100%; text-align: center;">File(s) uploaded with' + ' success!</div>');
        currentContainer.before(message);
        message.delay(1200).fadeIn(1200).hide(300, function () {
            $(this).remove();
            $('.file-upload-content').find('.wpfd_process_pause').remove();
            $('.file-upload-content').find('.wpfd_process_block').remove();
        });
    });

    $('.wpreview').unbind();
    $('.wpreview').each(function (i, el) {
            $('#upload_button', el).on('click', function (e) {
                currentContainer = $(el);
            });
            uploader.assignBrowse($(el).find('#upload_button'));
            uploader.assignDrop($(el));

        }
    );

    /**
     * Init the dropbox
     **/
    function initDropbox(dropbox) {
        var $fallback_id = $('.upload_input', dropbox).attr('id');
        dropbox.filedrop({
            paramname: 'pic',
            fallback_id: $fallback_id,
            maxfiles: 30,
            maxfilesize: Wpfd.maxfilesize,
            queuefiles: 2,
            data: {
                id_category: function () {
                    return $('input[name=id_category]', dropbox).val();
                }
            },
            url: wpfd_var.wpfdajaxurl + '?action=wpfd&task=files.upload',

            uploadFinished: function (i, file, response) {
                if (response.response === true) {
                    $.data(file).addClass('done');
                    $.data(file).find('img').data('id-file', response.datas.id_file);
                } else {
                    bootbox.alert(response.response);
                    $.data(file).remove();
                }
            },

            error: function (err, file) {
                switch (err) {
                    case 'BrowserNotSupported':
                        bootbox.alert(_wpfd_text('Your browser does not support HTML5 file uploads', 'Your browser does not support HTML5 file uploads!'));
                        break;
                    case 'TooManyFiles':
                        bootbox.alert(_wpfd_text('Too many files', 'Too many files') + '!');
                        break;
                    case 'FileTooLarge':
                        bootbox.alert(file.name + ' ' + _wpfd_text('is too large', 'is too large') + '!');
                        break;
                    default:
                        break;
                }
            },

            // Called before each upload is started
            beforeEach: function (file) {
                if (!wpfd_permissions.can_edit_category) {
                    bootbox.alert(wpfd_permissions.translate.wpfd_edit_category);
                    return false;
                }
            },

            uploadStarted: function (i, file, len) {
                var preview = $('<div class="wpfd_process_full" style="display: block;">' +
                    '<div class="wpfd_process_run" data-w="0" style="width: 0%;"></div>' +
                    '</div>');

                var reader = new FileReader();

                // Reading the file as a DataURL. When finished,
                // this will trigger the onload function above:
                reader.readAsDataURL(file);
                $('#preview', dropbox).before(preview);
//                        $('#dropbox').before(preview);

                // Associating a preview container
                // with the file, using jQuery's $.data():

                $.data(file, preview);
            },

            progressUpdated: function (i, file, progress) {
                $.data(file).find('.wpfd_process_run').width(progress + '%');
            },

            afterAll: function () {
                $('#wpreview .progress').delay(300).fadeIn(300).hide(300, function () {
                    $(this).remove();
                });
                $('#wpreview .uploaded').delay(300).fadeIn(300).hide(300, function () {
                    $(this).remove();
                });
                $('#wpreview .file').delay(1200).show(1200, function () {
                    $(this).removeClass('done placeholder');
                });
                $('.gritter-item-wrapper ').remove();
                var message = $('<div class="upload_message" style="width: 100%; text-align: center;">File(s) uploaded with' + ' success!</div>');
                $('#preview').before(message);
                message.delay(1200).fadeIn(1200).hide(300, function () {
                    $(this).remove();
                });
            },
            rename: function (name) {
                ext = name.substr(name.lastIndexOf('.'), name.length);
                name = name.substr(0, name.lastIndexOf('.'));

                var uint8array = new TextEncoderLite().encode(name);

                base64 = fromByteArray(uint8array);
                base64 = base64.replace("/", "|");
                return base64 + ext;
            }
        });
    }

    // init upload button
    function initUploadBtn() {
        $('.wpreview #upload_button').unbind();
        $('.wpreview').each(function (i, el) {
            $('#upload_button', el).on('click', function () {
                $('.upload_input', el).trigger('click');
                return false;
            });
        })

    }

});