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
            .on('click', this.config.unfollowThread.selector, this.unfollowTheThread);
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
        })

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
                        .html(response.messages.category_name).show()
                }

                if(!response.messages.category_name) {
                    addCategoryName.parent().removeClass('has-error');
                    addCategoryName.siblings('.alert').hide()
                }

                if(response.messages.category_description) {
                    addCategoryDescription.parent().addClass('has-error');
                    addCategoryDescription.siblings('.alert').addClass('alert-danger')
                        .html(response.messages.category_description).show()
                }

                if(!response.messages.category_description) {
                    addCategoryDescription.parent().removeClass('has-error');
                    addCategoryDescription.siblings('.alert').hide()
                }

                self.config.messageHolder.hide();
                return false;
            }

            // no errors
            // redirect
            window.location.href = response.lz;
            return false;
        })

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
    }
}

TheForum.init({
    theModal : $('#the_modal'),
    messageHolder : $('.message-holder'),

    followThread : $('.follow-thread'),
    unfollowThread : $('.unfollow-thread'),
    addForumCategory : $('.add-forum-category'),
    submitCategory : $('#trigger_add_category')
});
