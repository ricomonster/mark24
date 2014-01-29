// The Forum v1.0
var TheForum = {
    init : function(config)
    {
        // this acts as the constructor which gets
        // all of the elements/data that the init gets
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .on('click', this.config.addForumCategory.selector, this.showModalAddCategory)
            .on('click', this.config.submitCategory.selector, this.addCategory)
            .on('click', this.config.followThread.selector, this.followTheThread)
            .on('click', this.config.unfollowThread.selector, this.unfollowTheThread)
            .on('click', this.config.editThread.selector, this.showEditThread)
            .on('click', this.config.editThreadReply.selector, this.showEditThreadReply)
            .on('click', this.config.cancelThreadReply.selector, this.cancelThreadReply)
            .on('click', this.config.cancelThreadEdit.selector, this.cancelEditThread)
            .on('click', this.config.submitUpdateThread.selector, this.updateThread)
            .on('click', this.config.submitUpdateReply.selector, this.updateThreadReply);
    },

    // shows the modal for adding a category
    showModalAddCategory : function(e)
    {
        var self = TheForum;

        self.config.theModal.modal();
        // trigger ajax call
        $.ajax({
            url : '/ajax/modal/show-add-forum-category'
        }).done(function(response) {
            // load the returned response to the modal
            self.config.theModal.html(response);
        });

        e.preventDefault();
    },

    // submits the category
    addCategory : function(e)
    {
        var self                    = TheForum;
        var addCategoryName         = $('#category_name');
        var addCategoryDescription  = $('#category_description');

        self.config.messageHolder.show().find('span').text('Saving...');

        $.ajax({
            type : 'post',
            url : '/ajax/modal/submit-new-category',
            data : $('.add-category-modal').serialize(),
            dataType : 'json',
            async : false
        }).done(function(response) {
            // there's an error
            if(response.error) {
                // show errors
                if(response.messages.category_name) {
                    addCategoryName.parent().addClass('has-error');
                    addCategoryName.siblings('.alert').addClass('alert-danger')
                        .html(response.messages.category_name).show();
                }

                if(!response.messages.category_name) {
                    addCategoryName.parent().removeClass('has-error');
                    addCategoryName.siblings('.alert').hide();
                }

                if(response.messages.category_description) {
                    addCategoryDescription.parent().addClass('has-error');
                    addCategoryDescription.siblings('.alert').addClass('alert-danger')
                        .html(response.messages.category_description).show();
                }

                if(!response.messages.category_description) {
                    addCategoryDescription.parent().removeClass('has-error');
                    addCategoryDescription.siblings('.alert').hide();
                }

                self.config.messageHolder.hide();
                return false;
            }

            // no errors
            // redirect
            window.location.href = response.lz;
            return false;
        });

        e.preventDefault();
    },

    // follow the thread
    followTheThread : function(e)
    {
        var self    = TheForum;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Updating...');

        $.ajax({
            type : 'post',
            url : '/ajax/the-forum/follow-thread',
            data : { thread_id : $this.data('thread-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            // change the text and class
            $this.removeClass('follow-thread')
                .addClass('unfollow-thread').text('Unfollow');

            self.config.messageHolder.hide();
        });

        e.preventDefault();
    },

    // unfollow the thread
    unfollowTheThread : function(e)
    {
        var self    = TheForum;
        var $this   = $(this);

        var self    = TheForum;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Updating...');

        $.ajax({
            type : 'post',
            url : '/ajax/the-forum/unfollow-thread',
            data : { thread_id : $this.data('thread-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            // change the text and class
            $this.removeClass('unfollow-thread')
                .addClass('follow-thread').text('Follow');

            self.config.messageHolder.hide();
        });

        e.preventDefault();
    },

    showEditThread : function(e)
    {
        e.preventDefault();
        var element = $(this);
        // hide thread details
        $('.thread-details-holder').hide();
        // show form
        $('.update-thread-form').show();
    },

    showEditThreadReply : function(e)
    {
        e.preventDefault();
        var element = $(this);
        var threadId = element.attr('data-reply-id');
        // hide the reply details
        $('.thread-reply-holder[data-reply-id="'+threadId+'"]')
            .find('.thread-reply-details-holder')
            .hide();
        $('.update-reply-form[data-reply-id="'+threadId+'"]').show();
    },

    cancelEditThread : function(e)
    {
        e.preventDefault();
        var element = $(this);
        // hide thread details
        $('.thread-details-holder').show();
        // show form
        $('.update-thread-form').hide();
    },

    cancelThreadReply : function(e)
    {
        e.preventDefault();
        var element = $(this);
        var threadId = element.attr('data-reply-id');
        // hide the reply details
        $('.thread-reply-holder[data-reply-id="'+threadId+'"]')
            .find('.thread-reply-details-holder')
            .show();
        $('.update-reply-form[data-reply-id="'+threadId+'"]').hide();
    },

    updateThread : function(e)
    {
        e.preventDefault();
        var self        = TheForum;
        var thread      = $('.thread-details-holder');
        var form        = $('.update-thread-form');
        var title       = form.find('.thread-title');
        var description = form.find('.thread-description');

        if(title.val().length === 0 || description.val().length === 0) {
            if(title.val().length === 0) { title.parent().addClass('has-error'); }
            if(description.val().length === 0) { description.parent().addClass('has-error'); }
            return false;
        }
        
        // remove has error classes
        title.parent().removeClass('has-error');
        description.parent().removeClass('has-error');
        self.config.messageHolder.show()
            .find('span')
            .text('Updating...');
        
        // ajax!
        $.ajax({
            type        : 'post',
            url         : form.attr('action'),
            data        : form.serialize(),
            dataType    : 'json'
        }).done(function(response) {
            if(response.error) {
                self.config.messageHolder.show()
                    .find('span')
                    .text('There was an error processing your request. Please try again.');
            }

            if(!response.error) {
                // update the thread title
                thread.find('.thread-holder-title')
                    .text(title.val());
                // update the thread description
                thread.find('.thread-holder-description')
                    .text(description.val());
                // hide the form
                $('.update-thread-form').hide();
                // show the details
                thread.show();

                self.config.messageHolder.show()
                    .find('span')
                    .text('You have successfully updated the thread');
            }
            
            setTimeout(function() {
                self.config.messageHolder.hide();
            }, 5000);
        });
    },
    
    updateThreadReply : function(e)
    {
        e.preventDefault();
        var self                = TheForum;
        var element             = $(this);
        var replyThreadId       = element.attr('data-reply-id');
        var threadReplyHolder   = $('.thread-reply-holder[data-reply-id="'+replyThreadId+'"]');
        var form                = threadReplyHolder.find('.update-reply-form');
        var description         = form.find('.reply-description');
        // check if the reply description is empty
        if(description.val().length === 0) {
            description.parent().addClass('has-error');
            return false;
        }
        
        // unset the has error class
        description.parent().removeClass('has-error');
        self.config.messageHolder.show()
            .find('span')
            .text('Updating...');
        // ajax!
        $.ajax({
            type        : 'post',
            url         : form.attr('action'),
            data        : form.serialize(),
            dataType    : 'json'
        }).done(function(response) {
            if(response.error) {
                self.config.messageHolder.show()
                    .find('span')
                    .text('There was an error processing your request. Please try again.');
            }
            
            if(!response.error) {
                // update the reply description
                threadReplyHolder.find('.thread-reply-details-holder')
                    .find('.thread-reply-description')
                    .text(description.val());
                // hide the form
                form.hide();
                // show the reply description
                threadReplyHolder.find('.thread-reply-details-holder')
                    .show();
            }
        });
    }
};

TheForum.init({
    theModal : $('#the_modal'),
    messageHolder : $('.message-holder'),

    followThread : $('.follow-thread'),
    unfollowThread : $('.unfollow-thread'),
    addForumCategory : $('.add-forum-category'),
    submitCategory : $('#trigger_add_category'),

    editThread : $('.edit-thread'),
    editThreadReply : $('.edit-thread-reply'),
    cancelThreadEdit : $('.cancel-thread-edit'),
    cancelThreadReply : $('.cancel-thread-reply'),

    submitUpdateThread : $('.update-thread'),
    submitUpdateReply : $('.update-thread-reply')
});
