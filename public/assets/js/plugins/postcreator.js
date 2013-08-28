(function($) {
    var error = 0;
    // postcreator shits
    $('.postcreator-textarea').expandingTextarea();

    // note shits
    $('#note_content').on('focus', function() {
        $('#note .postcreator-hidden').show();
        $('#note .post-recipients').chosen();
    });

    $('#submit_note').on('click', function(e) {
        // validate first
        validateNote();

        if(error == 0) {
            $.ajax({
                type    : 'post',
                url     : $('#note form').attr('action'),
                data    : $('#note form').serialize(),

                success : function(response) {
                    // assuming no errors occured
                    // reset to former state
                    $('#note .postcreator-hidden').hide();
                    // let's reset the form elements
                    $('#note_content').val('');
                    $('#note_recipients').val('').trigger('chosen:updated');
                }
            });
        }

        e.preventDefault();
    });

    // alert shits
    $('#alert_content').on('focus', function() {
        $('#alert .postcreator-hidden').show();
        $('#alert .post-recipients').chosen();
    });

    $('#post_creator_options a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Create Group Modal
    $('#show_create_group').on('click', function(e) {
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show_create_group', function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    // functions
    function validateNote() {
        var noteContent     = $('#note_content');
        var noteRecipients  = $('#note_recipients');

        // set error counter to zero to reset
        error = 0;  

        // validate note fields
        if(noteContent.val() == '' || noteContent.length == 0) {
            noteContent.parent()
                .parent().addClass('has-error');
            noteContent.parent().parent().
                find('.alert').addClass('alert-danger').show().text('Your message is empty!');
            error++;
        } else {
            noteContent.parent()
                .parent().removeClass('has-error');
            noteContent.parent().parent().
                find('.alert').removeClass('alert-danger').hide().text('');
        }

        if(noteRecipients.val() == null || noteRecipients.length == 0) {
            noteRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            noteRecipients.parent().find('.alert')
                .addClass('alert-danger').show().text('Select recipients for your message');
            error++;
        } else {
            noteRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
            noteRecipients.parent().find('.alert')
                .removeClass('alert-danger').hide().text('');
        }
    }
})(jQuery)
