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
        var wrapperHeight = $('.post-creator-holder').height();
        var wrapperWidth = $('.post-creator-holder').width();
        var overlay = $('.post-creator-holder .overlay');

        $('.note-errors').empty().hide();
        validateNote();

        if(error == 0) {
            overlay.height(wrapperHeight).width(wrapperWidth)
                .slideDown();
            setTimeout(function() {
                $.ajax({
                    type    : 'post',
                    url     : $('#note form').attr('action'),
                    data    : $('#note form').serialize(),
                    async   : false

                }).done(function(response) {
                    // assuming no errors occured
                    // reset to former state
                    $('#note .postcreator-hidden').hide();
                    overlay.slideUp();
                    // let's reset the form elements
                    $('#note_content').val('');
                    $('#note_recipients').val('').trigger('chosen:updated');
                    // show the newest to stream
                    $('.post-stream-holder .post-stream').prepend(response)
                        .find('li:first').hide().slideDown(800);
                    $('.post-stream-holder .post-stream').find('.no-post-found').hide();
                });
            }, 800);
        }

        e.preventDefault();
    });

    // alert shits
    $('#alert_content').on('focus', function() {
        $('#alert .postcreator-hidden').show();
        $('#alert .post-recipients').chosen();
    });

    $('#submit_alert').on('click', function(e) {
        var wrapperHeight = $('.post-creator-holder').height();
        var wrapperWidth = $('.post-creator-holder').width();
        var overlay = $('.post-creator-holder .overlay');

        $('.alert-errors').empty().hide();
        validateAlert();

        if(error == 0) {
            overlay.height(wrapperHeight).width(wrapperWidth)
                .slideDown();
            setTimeout(function() {
                $.ajax({
                    type    : 'post',
                    url     : $('#alert form').attr('action'),
                    data    : $('#alert form').serialize(),
                    async   : false
                }).done(function(response) {
                    if(response) {
                        // assuming no errors occured
                        // reset to former state
                        overlay.slideUp();
                        $('#alert .postcreator-hidden').hide();
                        // let's reset the form elements
                        $('#alert_content').val('');
                        $('#alert_recipients').val('').trigger('chosen:updated');
                        // show the newest to stream
                        $('.post-stream-holder .post-stream').prepend(response)
                            .find('li:first').hide().slideDown(800);
                        $('.post-stream-holder .post-stream').find('.no-post-found').hide();
                    }
                });
            }, 800);
        }

        e.preventDefault();
    });

    // assignment shits
    $('#assignment_title, .assignment-due-date').on('focus', function() {
        $('#assignment .postcreator-hidden').show();
        $('#assignment .post-recipients').chosen();
    });

    $('#submit_assignment').on('click', function(e) {
        var wrapperHeight = $('.post-creator-holder').height();
        var wrapperWidth = $('.post-creator-holder').width();
        var overlay = $('.post-creator-holder .overlay');

        // validate first
        $('.assignment-errors').empty().hide();
        validateAssignment();

        if(error == 0) {
            overlay.height(wrapperHeight).width(wrapperWidth)
                .slideDown();
            setTimeout(function() {
                $.ajax({
                    type    : 'post',
                    url     : $('#assignment form').attr('action'),
                    data    : $('#assignment form').serialize(),
                    async   : false

                }).done(function(response) {
                    if(response) {
                        // assuming no errors occured
                        // reset to former state
                        overlay.slideUp();
                        $('#assignment .postcreator-hidden').hide();
                        // let's reset the form elements
                        $('#assignment_title').val('');
                        $('#assignment_due_date').val('');
                        $('#assignment_description').val('');
                        $('#assignment_lock').attr('checked', false);
                        $('#assignment_recipients').val('').trigger('chosen:updated');
                        // show the newest to stream
                        $('.post-stream-holder .post-stream').prepend(response)
                            .find('li:first').hide().slideDown(800);
                        $('.post-stream-holder .post-stream').find('.no-post-found').hide();
                    }
                });
            }, 800);
        }

        e.preventDefault();
    });

    // submits a quiz
    $(document).on('click', '#submit_quiz', function(e) {
        var wrapperHeight = $('.post-creator-holder').height();
        var wrapperWidth = $('.post-creator-holder').width();
        var overlay = $('.post-creator-holder .overlay');

        validateQuiz();

        if(error == 0) {
            overlay.height(wrapperHeight).width(wrapperWidth)
                .slideDown();
            setTimeout(function() {
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
                    overlay.slideUp();
                    // show the newest to stream
                    $('.post-stream-holder .post-stream').prepend(response)
                        .find('li:first').hide().slideDown(800);
                    $('.post-stream-holder .post-stream').find('.no-post-found').hide();
                });
            }, 800);
        }

        e.preventDefault();
    });

    $('#show_quiz_list').on('click', function(e) {
        // show modal
        $('#the_modal').modal('show');
        // ajax call
        $.ajax({
            type : 'get',
            url : '/ajax/modal/get-quiz-list',
        }).done(function(response) {
            // load the template to the modal
            $('#the_modal').html(response);
        });

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
        // validate fields first

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
                // hide form
                $('.edit-post-form[data-post-id="'+postId+'"]').hide();
                // check first if the edited post is an assignment or not
                var assignmentId = $('.edit-post-form[data-post-id="'+postId+'"]')
                    .find('.assignment-id');
                if(assignmentId.length === 0) {
                    var message = $('.edit-post-form[data-post-id="'+postId+'"]')
                        .find('.message-post');
                    // post is either a note or alert
                    // show post content with the new message
                    postHolder.find('.post-content').find('.post-content-container')
                        .find('.post').text(message.val()).show();
                }

                if(assignmentId.length !== 0) {
                    var title = $('.edit-post-form[data-post-id="'+postId+'"]')
                        .find('.assignment-title');
                    var description = $('.edit-post-form[data-post-id="'+postId+'"]')
                        .find('.assignment-description');

                    // post is an assignment
                    postHolder.find('.post-content').find('.post-content-container')
                        .find('.post').show();
                    // put the new title
                    postHolder.find('.post-content').find('.post-content-container')
                        .find('.post').find('strong').text(title.val());
                    // put the new description
                    postHolder.find('.post-content')
                        .find('.post-content-container')
                        .find('.post')
                        .find('.assignment-description')
                        .text(description.val());
                    // put the new due date
                    postHolder.find('.post-content').find('.post-content-container')
                        .find('.post').find('.assignment-description')
                        .text(response.due_date);
                }

                $('.message-holder').hide();
            }
        });

        e.preventDefault();
    });

    // removes uploaded file
    $(document).on('click', '.remove-uploaded-file', function(e) {
        var element = $(this);
        element.parent('.attached-file').remove();

        e.preventDefault();
    });

    // gets quiz details and append to the post creator
    $(document).on('click', '.quiz-to-load', function(e) {
        var element = $(this);
        $('.message-holder').show().find('span').text('Loading...');
        // ajax sir!
        $.ajax({
            url : '/ajax/modal/get-quiz-details',
            data : { quiz_id : element.attr('data-quiz-id') }
        }).done(function(response) {
            if(response) {
                $('#the_modal').modal('hide');
                // hide the default setup
                $('.quiz-first-choices').hide();
                // show the quiz module in postcreator
                // append!
                $('#quiz').append(response);
                // sets the chosen plugin
                $('#quiz .post-recipients').chosen();
                // date picker
                var nowTemp = new Date();
                var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

                var checkin = $('#quiz_due_date').datepicker({
                    onRender: function(date) {
                        return date.valueOf() < now.valueOf() ? 'disabled' : '';
                    },
                    format : 'yyyy-mm-dd'
                }).on('changeDate', function(ev) {
                    checkin.hide();
                }).data('datepicker');

                $('.message-holder').hide();
            }
        });

        e.preventDefault();
    });

    // functions
    function validateNote() {
        var noteContent     = $('#note_content');
        var noteRecipients  = $('#note_recipients');
        var noteErrors      = $('.note-errors');

        // set error counter to zero to reset
        error = 0;
        // validate note fields
        if(noteContent.val() == '' || noteContent.length == 0) {
            noteContent.parent()
                .parent().addClass('has-error');
            noteErrors.show();
            $('</p>').text('Your message is empty!').appendTo('.note-errors');
            error++;
        } else {
            noteContent.parent().parent().removeClass('has-error');
        }

        if(noteRecipients.val() == null || noteRecipients.length == 0) {
            noteRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            noteErrors.show();
            $('</p>').text('Select recipients for your message').appendTo('.note-errors');
            error++;
        } else {
            noteRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
        }
    }

    function validateAlert() {
        var alertContent     = $('#alert_content');
        var alertRecipients  = $('#alert_recipients');
        var alertErrors      = $('.alert-errors');

        // set error counter to zero to reset
        error = 0;

        // validate alert fields
        if(alertContent.val() == '' || alertContent.length == 0) {
            alertContent.parent()
                .parent().addClass('has-error');
            alertErrors.show();
            $('</p>').text('Your message is empty!').appendTo('.alert-errors');
            error++;
        } else {
            alertContent.parent().parent().removeClass('has-error');
        }

        if(alertRecipients.val() == null || alertRecipients.length == 0) {
            alertRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            alertErrors.show();
            $('</p>').text('Select recipients for your message').appendTo('.alert-errors');
            error++;
        } else {
            alertRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
        }
    }

    function validateQuiz() {
        var quizDueDate     = $('#quiz_due_date');
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

    function validateAssignment() {
        var assignmentErrors = $('.assignment-errors');
        var assignmentTitle = $('#assignment_title');
        var assignmentDueDate = $('#assignment_due_date');
        var assignmentDescription = $('#assignment_description');
        var assignmentRecipients = $('#assignment_recipients');

        error = 0;
        if(assignmentTitle.val() == '' || assignmentTitle.val().length == 0) {
            assignmentTitle.parent().addClass('has-error');
            assignmentErrors.show();
            $('</p>').text('You must type an assignment title').appendTo('.assignment-errors');
            error++;
        } else {
            assignmentTitle.parent().removeClass('has-error');
        }

        if(assignmentDueDate.val() == '' || assignmentDueDate.val().length == 0) {
            assignmentDueDate.parent().addClass('has-error');
            assignmentErrors.show();
            $('</p>').text('You must set a due date').appendTo('.assignment-errors');
            error++;
        } else {
            assignmentDueDate.parent().removeClass('has-error');
        }

        if(assignmentDescription.val() == '' || assignmentDescription.val().length == 0) {
            assignmentDescription.parent().addClass('has-error');
            assignmentErrors.show();
            $('</p>').text('You must type a description').appendTo('.assignment-errors');
            error++;
        } else {
            assignmentDescription.parent().removeClass('has-error');
        }

        if(assignmentRecipients.val() == null || assignmentRecipients.length == 0) {
            assignmentRecipients.parent().find('.chosen-container .chosen-choices')
                .addClass('has-error-recipients');
            assignmentErrors.show();
            $('</p>').text('Select recipients for your message').appendTo('.assignment-errors');
            error++;
        } else {
            assignmentRecipients.parent().find('.chosen-container .chosen-choices')
                .removeClass('has-error-recipients');
        }
    }

})(jQuery);