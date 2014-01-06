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
            .ready(this.initialState)
            .on('click', this.config.deletePost.selector, this.confirmDeletePost)
            .on('click', this.config.triggerDeletePost.selector, this.deletePost)
            .on('click', this.config.linkPost.selector, this.linkThePost)
            .on('click', this.config.likePost.selector, this.likeThePost)
            .on('click', this.config.unlikePost.selector, this.unlikeThePost);
    },

    initialState : function()
    {
        var self = CommentCreator;
        self.config.commentBox.expandingTextarea();
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
    },

    // shows the link of the post
    linkThePost : function(e)
    {
        var self = Poststream;
        var $this = $(this);

        self.config.theModal.modal('show');

        $.ajax({
            url : '/ajax/modal/link-post',
            data : { post_id : $this.data('post-id') },
            async : false
        }).done(function(response) {
            self.config.theModal.html(response);
        });

        e.preventDefault();
    },

    // like the post
    likeThePost : function(e)
    {
        var self = Poststream;
        var $this = $(this);
        var postId = $this.attr('data-post-id');

        $.ajax({
            type : 'post',
            url : '/ajax/like/like-post',
            data : {
                post_id : postId
            },
            dataType : 'json'
        }).done(function(response) {
            if(!response.error) {
                if(response.likers) {
                    $('.post-holder[data-post-id="'+postId+'"]').find('.user-post-likes')
                        .show().empty().append(response.likers);
                    // make the link as unlike
                    $this.removeClass('like-post').addClass('unlike-post')
                        .html('<i class="fa fa-thumbs-down"></i> Unlike it');
                }
            }
        });

        e.preventDefault();
    },

    // unlike the post
    unlikeThePost : function(e)
    {
        var self = Poststream;
        var $this = $(this);
        var postId = $this.attr('data-post-id');
        $.ajax({
            type : 'post',
            url : '/ajax/like/unlike-post',
            data : {
                post_id : postId
            },
            dataType : 'json'
        }).done(function(response) {
            if(!response.error) {
                if(response.like_count == 0) {
                    $('.post-holder[data-post-id="'+postId+'"]')
                        .find('.user-post-likes').hide();
                }

                if(response.like_count != 0) {
                    $('.post-holder[data-post-id="'+postId+'"]').find('.user-post-likes')
                        .show().empty().append(response.likers);
                }

                // make the link as unlike
                $this.removeClass('unlike-post').addClass('like-post')
                    .html('<i class="fa fa-thumbs-up"></i> Like it');
            }
        })

        e.preventDefault();
    }
};

Poststream.init({
    messageHolder : $('.message-holder'),
    theModal : $('#the_modal'),

    deletePost : $('.delete-post'),
    linkPost : $('.link-post'),
    likePost : $('.like-post'),
    unlikePost : $('.unlike-post'),
    triggerDeletePost : $('#trigger_delete_post')
});
