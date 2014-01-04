$(function() {
    'use strict';
    // notes
    $('.fileupload-notes').fileupload({
        url: '/ajax/thelibrary/upload-file',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.error) {
                $('#notes .files').show().append(
                    '<li class="attached-file error-upload clearfix">'+
                    '<a href="#" class="remove-uploaded-file">'+
                    '<span class="glyphicon glyphicon-remove"></span>'+
                    '</a>'+
                    '<span class="upload-filename">'+data.result.file_name+'</span>'+
                    '<span class="error-upload-message pull-right text-muted">'+data.result.message+'</span>'+
                    '</li>');
            }

            if(!data.result.error) {
                $('#notes .files').show().append(
                    '<li class="attached-file clearfix">'+
                    '<a href="#" class="remove-uploaded-file"><span class="glyphicon glyphicon-remove"></span></a>'+
                    '<span class="upload-filename">'+data.result.file.file_name+'</span>'+
                    '<input type="hidden" name="attached-file-id[]" value="'+
                    data.result.file.file_library_id+'">'+
                    '</li>');
            }

            $('.progress').hide();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.progress').show();
            $('.progress .progress-bar').css(
                'width',
                progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    // assignment
    $('.fileupload-assignment').fileupload({
        url: '/ajax/thelibrary/upload-file',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.error) {
                $('#assignment .files').show().append(
                    '<li class="attached-file error-upload clearfix">'+
                    '<a href="#" class="remove-uploaded-file">'+
                    '<span class="glyphicon glyphicon-remove"></span>'+
                    '</a>'+
                    '<span class="upload-filename">'+data.result.file_name+'</span>'+
                    '<span class="error-upload-message pull-right text-muted">'+data.result.message+'</span>'+
                    '</li>');
            }

            if(!data.result.error) {
                $('#assignment .files').show().append(
                    '<li class="attached-file clearfix">'+
                    '<a href="#" class="remove-uploaded-file"><span class="glyphicon glyphicon-remove"></span></a>'+
                    '<span class="upload-filename">'+data.result.file.file_name+'</span>'+
                    '<input type="hidden" name="attached-file-id[]" value="'+
                    data.result.file.file_library_id+'">'+
                    '</li>');
            }

            $('.progress').hide();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.progress').show();
            $('.progress .progress-bar').css(
                'width',
                progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
