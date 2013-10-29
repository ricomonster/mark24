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
            .on('click', this.config.deletePost.selector, this.confirmDeletePost);
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
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(response.error) {
                // there are errors
            }

            if(!response.error) {

            }
        })

        e.preventDefault();
    }
}

Poststream.init({
    messageHolder : $('.message-holder'),
    theModal : $('#the_modal'),

    deletePost : $('.delete-post')
});
