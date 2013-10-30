var Poststream = {
    init : function(config)
    {
        // initialize all the things
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .on('click', this.config.deletePost.selector, this.confirmDeletePost)
            .on('click', this.config.triggerDeletePost.selector, this.deletePost);
    },

    // show confirmation on deleting a post
    confirmDeletePost : function(e)
    {
        var self = Poststream;
        var $this = $(this);

        self.config.theModal.modal('show');

        $.ajax({
            url : '/ajax/modal/confirm-delete-post',
            data : { post_id : $this.data('post-id') },
            async : false
        }).done(function(response) {
            self.config.theModal.html(response);
        })

        e.preventDefault();
    },

    // cancel delete post
    cancelDeletePost : function(e)
    {
        var self = Poststream;
        self.config.theModal.modal('hide');
        e.preventDefault();
    },

    // delete's the post
    deletePost : function(e)
    {
        var self = Poststream;
        var $this = $(this);

        self.config.messageHolder.show().find('span').text('Deleting...');
        // trigger ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/modal/delete-post',
            data : { post_id : $this.data('post-id') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(response.error) {
                // there's an error
            }

            if(!response.error) {
                // no error
                self.config.messageHolder.hide();
                // hide modal
                self.config.theModal.modal('hide');
                // remove post in the stream
                setTimeout(function() {
                    $('.post-holder[data-post-id="'+$this.data('post-id')+'"]')
                        .slideUp(400);
                }, 400);
            }
        });
    }
}

Poststream.init({
    messageHolder : $('.message-holder'),
    theModal : $('#the_modal'),

    deletePost : $('.delete-post'),
    triggerDeletePost : $('#trigger_delete_post')
});
