(function($) {
    var error = 0;
    // postcreator shits
    $('.postcreator-textarea').expandingTextarea();
    // tab shits
    $('#post_creator_options a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

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
                async   : false

            }).done(function(response) {
                // assuming no errors occured
                // reset to former state
                $('#note .postcreator-hidden').hide();
                // let's reset the form elements
                $('#note_content').val('');
                $('#note_recipients').val('').trigger('chosen:updated');
                // show the newest to stream
                $('.post-stream-holder .post-stream').prepend(response)
                    .find('li:first').hide().slideDown(800);
                $('.post-stream-holder .post-stream').find('.no-post-found').hide();
            });
        }

        e.preventDefault();
    });

    // alert shits
    $('#alert_content').on('focus', function() {
        $('#alert .postcreator-hidden').show();
        $('#alert .post-recipients').chosen();
    });

    $('#submit_alert').on('click', function(e) {
        validateAlert();

        if(error == 0) {
            $.ajax({
                type    : 'post',
                url     : $('#alert form').attr('action'),
                data    : $('#alert form').serialize(),
                async   : false
            }).done(function(response) {
                // assuming no errors occured
                // reset to former state
                $('#alert .postcreator-hidden').hide();
                // let's reset the form elements
                $('#alert_content').val('');
                $('#alert_recipients').val('').trigger('chosen:updated');
                // show the newest to stream
                $('.post-stream-holder .post-stream').prepend(response)
                    .find('li:first').hide().slideDown(800);
                $('.post-stream-holder .post-stream').find('.no-post-found').hide();
            })
        }

        e.preventDefault();
    });

    // submits a quiz
    $('#submit_quiz').on('click', function(e) {
        validateQuiz();

        if(error == 0) {
            $.ajax({
                type    : 'post',
                url     : $('#quiz form').attr('action'),
                data    : $('#quiz form').serialize(),
                async   : false
            }).done(function(response) {
                // assuming no errors occured
                // remove the quiz form
                $('#quiz form').remove();
                // show the create quiz links
                $('#quiz').find('.quiz-first-choices').show();
                // let's reset the form elements
                $('#quiz_recipients').val('').trigger('chosen:updated');
                // show the newest to stream
                $('.post-stream-holder .post-stream').prepend(response)
                    .find('li:first').hide().slideDown(800);
                $('.post-stream-holder .post-stream').find('.no-post-found').hide();
            })
        }

        e.preventDefault();
    });

    // shows the update post form
    $(document).on('click', '.edit-post', function(e) {
        var $this = $(this);
        var postId = $this.data('post-id');
        var postHolder = $('.post-holder[data-post-id="'+postId+'"]');

        // hide first the post
        postHolder.find('.post-content').find('.post-content-container')
            .find('.post').hide();
        // show the form
        $('.edit-post-form[data-post-id="'+postId+'"]').show();

        e.preventDefault();
    });

    // cancel the editing of the post
    $(document).on('click', '.cancel-edit-post', function(e) {
        var $this = $(this);
        var postId = $this.data('post-id');
        var postHolder = $('.post-holder[data-post-id="'+postId+'"]');

        // hide first the post
        postHolder.find('.post-content').find('.post-content-container')
            .find('.post').show();
        // show the form
        $('.edit-post-form[data-post-id="'+postId+'"]').hide();

        e.preventDefault();
    });

    // update!
    $(document).on('click', '.save-edit-post', function(e) {
        var $this = $(this);
        var postId = $this.data('post-id');
        var postHolder = $('.post-holder[data-post-id="'+postId+'"]');

        $('.message-holder').show().find('span').text('Updating...');

        $.ajax({
            type        : 'post',
            url         : $('.edit-post-form[data-post-id="'+postId+'"]').attr('action'),
            data        : $('.edit-post-form[data-post-id="'+postId+'"]').serialize(),
            dataType    : 'json',
            async       : false
        }).done(function(response) {
            if(response.error) {
                // there's an error
            }

            if(!response.error) {
                // no error
                var message = $('.edit-post-form[data-post-id="'+postId+'"]').find('.message-post')
                // hide form
                $('.edit-post-form[data-post-id="'+postId+'"]').hide();
                // show post content with the new message
                postHolder.find('.post-content').find('.post-content-container')
                    .find('.post').text(message.val()).show();

                $('.message-holder').hide();
            }
        })

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

    function validateAlert() {
        var alertContent     = $('#alert_content');
        var alertRecipients  = $('#alert_recipients');

        // set error counter to zero to reset
        error = 0;

        // validate alert fields
        if(alertContent.val() == '' || alertContent.length == 0) {
            alertContent.parent()
                .parent().addClass('has-error');
            alertContent.parent().parent().
                find('.alert').addClass('alert-danger').show().text('Your message is empty!');
            error++;
        } else {
            alertContent.parent()
                .parent().removeClass('has-error');
            alertContent.parent().parent().
                find('.alert').removeClass('alert-danger').hide().text('');
        }

        if(alertRecipients.val() == null || alertRecipients.length == 0) {
            alertRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            alertRecipients.parent().find('.alert')
                .addClass('alert-danger').show().text('Select recipients for your message');
            error++;
        } else {
            alertRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
            alertRecipients.parent().find('.alert')
                .removeClass('alert-danger').hide().text('');
        }
    }

    function validateQuiz() {
        var quizDueDate     = $('#quiz_due_date')
        var quizRecipients  = $('#quiz_recipients');

        // set error counter to zero to reset
        error = 0;

        // validate alert fields
        if(quizDueDate.val() == '' || quizDueDate.length == 0) {
            quizDueDate.parent()
                .parent().addClass('has-error');
            quizDueDate.parent().parent().
                find('.alert').addClass('alert-danger').show().text('Please specify a due date');
            error++;
        } else {
            quizDueDate.parent()
                .parent().removeClass('has-error');
            quizDueDate.parent().parent().
                find('.alert').removeClass('alert-danger').hide().text('');
        }

        if(quizRecipients.val() == null || quizRecipients.length == 0) {
            quizRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            quizRecipients.parent().find('.alert')
                .addClass('alert-danger').show().text('Select recipients for your message');
            error++;
        } else {
            quizRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
            quizRecipients.parent().find('.alert')
                .removeClass('alert-danger').hide().text('');
        }
    }

})(jQuery)
