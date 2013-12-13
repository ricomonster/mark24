var Chat = {
    init : function(config)
    {
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .ready(this.checkChatDetails)
            .ready(this.fetchMessages)
            .on('keydown', this.config.messageBox.selector, this.checkKeydown)
    },

    checkChatDetails : function()
    {
        var self = Chat;
        var $this = $(this);

        $.ajax({
            type : 'get',
            url : '/ajax/chat/check-chat-details',
            data : {
                group_id : self.config.groupChatWrapper.data('group-id')
            },
        }).done(function(response) {

        });
    },

    fetchMessages : function()
    {
        var self = Chat;

        setInterval(function() {
            console.log('fetch data');
        }, 8000);
    },

    checkKeydown : function(e)
    {
        var self = Chat;
        var $this = $(this);

        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            // submit comment
            self.submitChat($this);
            return;
        }
    },

    submitChat : function(element)
    {
        if(element.val() !== '' || element.val().length !== 0) {
            console.log(element.val());
        }

        console.log('boom');
    }
};

Chat.init({
    groupChatWrapper : $('.group-chat-wrapper'),

    messageBox : $('.message-box')
});
