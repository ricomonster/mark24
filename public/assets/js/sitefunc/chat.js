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

        self.config.messageHolder.show().find('span').text('Loading... Fetching data...');
        $.ajax({
            type : 'get',
            url : '/ajax/chat/check-chat-details',
            data : {
                group_id : self.config.groupChatWrapper.data('group-id')
            },
        }).done(function(response) {
            self.config.conversationId = response.conversation_id;
            self.config.messageHolder.show().find('span').text('Initializing chat...');

            if(response.conversation) {
                // load the messages if there are
                // trigger the message fetcher
                self.getMessages();
            }

            self.fetchMessages();
            self.config.messageHolder.hide();
        });
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

        if(element.val() !== '' || element.val().length !== 0) {
            $.ajax({
                type : 'post',
                url : '/ajax/chat/send-message',
                data : {
                    conversation_id : self.config.conversationId,
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
        var lastId = $('.chat-content').last().attr('data-chat-id');

        console.log(lastId);

        $.ajax({
            url : '/ajax/chat/fetch-messages',
            data : {
                conversation_id : self.config.conversationId,
                last_id : lastId
            }
        }).done(function(response) {
            // load to the template
            self.config.chatStream.append(response);
            self.scroller();
        })
    },

    fetchMessages : function()
    {
        var self = Chat;

        if(self.config.conversationId != 0) {
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
    conversationId : 0,

    messageHolder : $('.message-holder'),
    groupChatWrapper : $('.group-chat-wrapper'),
    chatStream : $('.chat-stream'),

    messageBox : $('.message-box')
});
