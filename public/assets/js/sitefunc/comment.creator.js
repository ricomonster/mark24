// Comment Creator for the Post Stream
var CommentCreator = {
    init : function(config) {
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function() {
        $(document)
            .on('click', this.config.showCommentForm.selector, this.showForm)
            .on('keyup', this.config.commentBox.selector, this.initializeComment)
            .on('keydown', this.config.commentBox.selector, this.checkKeydown)
            .on('click', this.config.submitCommentButton.selector, this.submitComment)
            .on('submit', this.config.commentForm.selector, this.submitFormComment);
    },
    
    showForm : function(e)
    {
        var self = CommentCreator;
        var $this = $(this);
        
        $('.comment-form-holder[data-post-id="'+$this.data('post-id')+'"]')
            .show();
        
        e.preventDefault();
    },

    initializeComment : function() {
        var self = CommentCreator;
        var $this = $(this);
        var postId = $this.data('post-id');
        var submitButton = $('.submit-comment[data-post-id="'+postId+'"]');

        if($this.val().length !== 0) {
            submitButton.removeAttr('disabled');
        }

        if($this.val().length === 0) {
            submitButton.attr('disabled', 'disabled');
        }
    },

    checkKeydown : function(e)
    {
        var self = CommentCreator;
        var $this = $(this);

        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            // submit comment
            self.submitComment($this);
            return;
        }
    },

    submitComment : function(content) {
        var self = CommentCreator;
        var $this = content;
        var postId = $this.data('post-id');
        var commentBox = $('.post-comment[data-post-id="'+postId+'"]');
        var commentStreamHolder = $('.post-comment-holder[data-post-id="'+postId+'"]');

        $.ajax({
            type : 'post',
            url : '/ajax/comment-creator/add-comment',
            data : {
                post_id : postId,
                comment : commentBox.val()
            },
            async : false
        }).done(function(response) {
            // unset content of the form
            $this.val('').blur();

            // unhide the comment stream wrapper
            commentStreamHolder.show()
                .find('ul').append(response).find('li:last-child')
                .hide().slideDown(200);
            // show comment in the stream
        });
    }
};

CommentCreator.init({
    commentBox : $('.post-comment'),
    submitCommentButton : $('.submit-comment'),
    commentForm : $('.comment-form'),
    showCommentForm : $('.show-comment-form')
});
