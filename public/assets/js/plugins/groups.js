(function($) {
    // Join Group Modal
    $('#show_join_group').on('click', function(e) {
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show_join_group', function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    // some modal functions
    $(document).on('click', '#trigger_join_group', function(e) {
        joinGroup();
        e.preventDefault();
    }).on('submit', '.join-group-modal', function(e) {
        joinGroup();
        e.preventDefault();
    }).on('click', 'button#trigger_create_group', function(e) {
        createGroup(); e.preventDefault();
    }).on('submit', '.create-group-modal', function(e) {
        createGroup(); e.preventDefault();
    });

    // Create Group Modal
    $('#show_create_group').on('click', function(e) {
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show_create_group', function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });    

    // show group settings
    $('#show_settings_modal').on('click', function(e) {
        var $this = $(this);

       $('#the_modal').modal('show');
        $.get('/ajax/modal/show-settings-group', { group_id : $this.data('group-id') }, function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    // updates the group settings
    $(document).on('click', '#trigger_update_group', function(e) {
        var thisButton = $(this);

        $('.message-holder').show().find('span').text('Saving...');
        // reset status
        $('.create-group-modal .form-group').removeClass('has-error');
        $('.create-group-modal .alert').hide();

        thisButton.attr('disabled');

        $.ajax({
            type        : 'post',
            url         : '/ajax/modal/submit-group-update',
            data        : $('.update-group-settings').serialize(),
            dataType    : 'json',
            async       : false

        }).done(function(response) {
            // check if there's an error
            if(response.error) {
                // set up the error message to show on the modal
                if(response.messages.groupName) {
                    // with error
                    $('#group_name').parent().addClass('has-error');
                    $('#group_name').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupName).show();
                } else {
                    // clean up error state
                    $('#group_name').parent().removeClass('has-error');
                    $('#group_name').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                if(response.messages.groupSize) {
                    // with error
                    $('#group_size').parent().addClass('has-error');
                    $('#group_size').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupSize).show();
                } else {
                    // clean up error state
                    $('#group_size').parent().removeClass('has-error');
                    $('#group_size').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                if(response.messages.groupDescription) {
                    // with error
                    $('#group_description').parent().addClass('has-error');
                    $('#group_description').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupDescription).show();
                } else {
                    // clean up error state
                    $('#group_description').parent().removeClass('has-error');
                    $('#group_description').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                thisButton.removeAttr('disabled');
                $('.message-holder').hide();
            } else {
                $('#the_modal').modal('hide');
                // apply the changes in the template
                $('.group-name').text($('#group_name').val());
                $('.group-description-holder').text($('#group_description').val());
                $('.message-holder').hide();
            }
        });
    });

    // shows confirmation to delete the group
    $(document).on('click', '#confirm-delete-group', function(e) {
        var $this = $(this);

        $('.message-holder').show().find('span').text('Loading...');
        // hide modal
        $('#the_modal').modal('hide');

        // trigger ajax call to get the confirmation template
        $.get(
            '/ajax/modal/confirm-delete-group',
            { group_id : $this.data('group-id') },
            function(response) {
                $('#the_modal').modal('show')
                $('#the_modal').html(response);
                $('.message-holder').hide();
            });

        e.preventDefault();
    });

    // delete of group is confirmed to yes
    $(document).on('click', '#trigger_delete-group', function(e) {
        var $this = $(this);

        $('.message-holder').show().find('span').text('Saving...');

        // trigger ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/modal/delete-group',
            data : { group_id : $this.data('group-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            // there's an error processing the request
            if(response.error) {

            }

            if(!response.error) {
                // redirect page to home
                window.location.href = response.lz;
                return false;
            }
        });

        e.preventDefault();
    });

    // group lock function
    $(document).on('click', '.lock-group', function(e) {
        var $this = $(this);

        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type : 'post',
            url : '/ajax/group/lock-group',
            data : { group_id : $this.data('group-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(!response.error) {
                $('.message-holder').hide();

                $this.removeClass('lock-group').addClass('unlock-group')
                    .html('<i class="group-code-icon fa fa-lock"></i>');
                // change group code to LOCKED
                $('.group-code').val('LOCKED');
                $('.message-holder').hide();

                $('.message-holder').show().find('span').text('The group code has been unlocked');

                setInterval(function() {
                    $('.message-holder').hide();
                }, 3000)
            }
        })

        e.preventDefault();
    });

    // group unlock function
    $(document).on('click', '.unlock-group', function(e) {
        var $this = $(this);
        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type : 'post',
            url : '/ajax/group/unlock-group',
            data : { group_id : $this.data('group-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(!response.error) {
                $('.message-holder').hide();
                // change icon
                $this.removeClass('unlock-group').addClass('lock-group')
                    .html('<i class="group-code-icon fa fa-unlock"></i>');

                // change group code to LOCKED
                $('.group-code').val(response.group_code);

                $('.message-holder').show().find('span').text('The group code has been locked');

                setInterval(function() {
                    $('.message-holder').hide();
                }, 3000);
            }
        })

        e.preventDefault();
    });

    // resets the group code
    $(document).on('click', '.reset-group-code', function(e) {
        var $this = $(this);
        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type : 'post',
            url : '/ajax/group/reset-group-code',
            data : { group_id : $this.data('group-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(!response.error) {
                $('.message-holder').hide();

                $('.group-code').val(response.group_code);
                $('.message-holder').hide();

                $('.message-holder').show().find('span').text('The group code has been reset');

                setInterval(function() {
                    $('.message-holder').hide();
                }, 3000)
            }
        })

        e.preventDefault();
    });

    // student leaving group
    $(document).on('click', '#leave_group', function(e) {
        var $this = $(this);

        // show the modal
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show-withdraw-group', { group_id : $this.data('group-id') }, function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    $(document).on('click', '#trigger_withdraw_group', function() {
        var $this = $(this);
        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type : 'post',
            url : '/ajax/group/withdraw-group',
            data : { group_id : $this.data('group-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(!response.error) {
                // redirect to home
                window.location.href = response.lz;
            }
        })

        e.preventDefault();
    });

    function createGroup() {
        var thisButton = $('button#trigger_create_group');

        $('.message-holder').show().find('span').text('Saving...');
        // reset status
        $('.create-group-modal .form-group').removeClass('has-error');
        $('.create-group-modal .alert').hide();

        thisButton.attr('disabled');

        $.ajax({
            type        : 'post',
            url         : '/ajax/modal/create_group',
            data        : $('.create-group-modal').serialize(),
            dataType    : 'json',
            async       : false

        }).done(function(response) {
            // check if there's an error
            if(response.error) {
                // set up the error message to show on the modal
                if(response.messages.groupName) {
                    // with error
                    $('#group_name').parent().addClass('has-error');
                    $('#group_name').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupName).show();
                } else {
                    // clean up error state
                    $('#group_name').parent().removeClass('has-error');
                    $('#group_name').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                if(response.messages.groupSize) {
                    // with error
                    $('#group_size').parent().addClass('has-error');
                    $('#group_size').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupSize).show();
                } else {
                    // clean up error state
                    $('#group_size').parent().removeClass('has-error');
                    $('#group_size').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                if(response.messages.groupDescription) {
                    // with error
                    $('#group_description').parent().addClass('has-error');
                    $('#group_description').siblings('.alert').addClass('alert-danger')
                        .html(response.messages.groupDescription).show();
                } else {
                    // clean up error state
                    $('#group_description').parent().removeClass('has-error');
                    $('#group_description').siblings('.alert').removeClass('alert-danger')
                        .empty().hide();
                }

                thisButton.removeAttr('disabled');
                $('.message-holder').hide();
            } else {
                // no error get LZ link so page will redirect
                window.location.href = response.lz_link;
            }
        });
    }

    function joinGroup() {
        var thisButton = $('#trigger_join_group');

        $('.message-holder').show().find('span').text('Saving...');

        thisButton.attr('disabled');
        $('.join-group-modal .form-group').removeClass('has-error');
        $('.join-group-modal .alert').hide();

        $.ajax({
            type        : 'post',
            url         : '/ajax/modal/join_group',
            data        : $('.join-group-modal').serialize(),
            dataType    : 'json',
            async       : false
        }).done(function(response) {
            if(response.error) {
                // show error message
                $('#group_code').parent().addClass('has-error');
                $('#group_code').siblings('.alert').addClass('alert-danger')
                    .html(response.message).show();

                thisButton.removeAttr('disabled');

                $('.message-holder').hide();

                return false;
            }

            // no error get LZ link so page will redirect
            window.location.href = response.lz_link;
            return false;
        });
    }
})(jQuery)
