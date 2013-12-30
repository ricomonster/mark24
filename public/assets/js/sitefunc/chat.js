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
            .on('keydown', this.config.messageBox.selector, this.checkKeydown)
    },

    checkChatDetails : function()
    {
        var self = Chat;
        var $this = $(this);
        var groupId = self.config.groupChatWrapper.attr('data-group-id');
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        self.config.messageHolder.show().find('span').text('Loading... Fetching data...');
        $.ajax({
            url : '/ajax/chat/check-chat-details',
            data : {
                conversation_id : conversationId,
                group_id : groupId
            },
            dataType : 'json'
        }).done(function(response) {
            if(response.chats) {
                self.config.messageHolder.show().find('span').text('Initializing...');
                self.getMessages();
            }

            self.fetchMessages();
            self.config.messageHolder.hide();
        })
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
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        if(element.val() !== '' || element.val().length !== 0) {
            $.ajax({
                type : 'post',
                url : '/ajax/chat/send-message',
                data : {
                    conversation_id : conversationId,
                    message : element.val()
                },
                dataType : 'json'
            }).done(function(response) {
                // trigger fetch message
                element.val('');
                self.getMessages(response.last_conversation_id);
            });
        }
    },

    getMessages : function(newConversationId)
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');
        var lastId = $('.chat-content').last().attr('data-chat-id');

        $.ajax({
            url : '/ajax/chat/fetch-messages',
            data : {
                conversation_id : conversationId,
                last_id : lastId
            }
        }).done(function(response) {
            // load to the template
            if(response) {
                self.config.chatStream.append(response);
                self.scroller();
            }
        })
    },

    fetchMessages : function()
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        if(conversationId != 0) {
            setInterval(function() {
                self.getMessages();
            }, 8000);
        }
    },

    scroller : function()
    {
        var self = Chat;
        var height = 0;
        $('.chat-stream .chat-content').each(function(i, value){
            height += parseInt($(this).height());
        });

        height += '';

        $('.chat-stream').animate({scrollTop: height});
    }
};

Chat.init({
    messageHolder : $('.message-holder'),
    groupChatWrapper : $('.group-chat-wrapper'),
    chatStream : $('.chat-stream'),

    messageBox : $('.message-box')
});
