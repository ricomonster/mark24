// Comment Creator for the Post Stream
var CommentCreator = {
    init : function(config) {
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function() {
        $(document)
            .on('keyup', this.config.commentBox.selector, this.initializeComment)
            .on('click', this.config.submitCommentButton.selector, this.submitComment)
            .on('submit', this.config.commentForm.selector, this.submitFormComment);
    },

    initializeComment : function() {
        var self = CommentCreator;
        var $this = $(this);
        var postId = $this.data('post-id');
        var submitButton = $('.submit-comment[data-post-id="'+postId+'"]');

        if($this.val().length != 0) {
            submitButton.removeAttr('disabled');
        }

        if($this.val().length == 0) {
            submitButton.attr('disabled', 'disabled');
        }
    },

    submitComment : function(e) {
        var self = CommentCreator;
        var $this = $(this);
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
            $this.attr('disabled', 'disabled');
            commentBox.val('').blur();

            // unhide the comment stream wrapper
            commentStreamHolder.show()
                .find('ul').append(response).find('li:last-child')
                .hide().slideDown(200);
            // show comment in the stream
        })

        e.preventDefault();
    }
}

CommentCreator.init({
    commentBox : $('.post-comment'),
    submitCommentButton : $('.submit-comment'),
    commentForm : $('.comment-form')
})
