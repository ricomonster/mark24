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
            .on('click', this.config.stopGroupChat.selector, this.showConfirmStopGroupChat)
            .on('click', this.config.confirmStopGroupChat.selector, this.stopGroupChat);
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
            self.checkOnlineUsers();
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

        clearInterval(self.config.fetchInterval);
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
                self.fetchMessages();
            });
        }
    },

    fetchMessages : function()
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        if(conversationId != 0) {
            self.config.fetchInterval = setInterval(function() {
                self.getMessages();
                // check also if the current conversation is close
                self.checkConversation();
            }, 8000);
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

    checkConversation : function()
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');
        $.ajax({
            url : '/ajax/chat/check-status',
            data : {
                conversation_id : conversationId
            },
            dataType : 'json'
        }).done(function(response) {
            if(response.close) {
                // clear intervals
                clearInterval(self.config.fetchInterval);
                clearInterval(self.config.onlineInterval);

                var timer = 10;
                // disable textarea
                self.config.messageBox.val('').blur()
                    .attr('disabled', 'disabled');
                // create a timer prompting that the
                // chat session will close in x seconds
                setInterval(function() {
                    self.config.messageHolder.show().find('span')
                        .text('Closing chat session in '+timer+' seconds...');
                    timer--;
                    if(timer == 0) {
                        window.location.href = response.lz;
                    }
                }, 1000);
            }
        })
    },

    checkOnlineUsers : function()
    {
        var self = Chat;
        var groupId = self.config.groupChatWrapper.attr('data-group-id');
        self.config.onlineInterval = setInterval(function() {
            $.ajax({
                url : '/ajax/chat/check-online-users',
                data : {
                    group_id : groupId
                }
            }).done(function(response) {
                if(response) {
                    $('.student-lists').empty().append(response);
                }
            });
        }, 120000);
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
    },

    showConfirmStopGroupChat : function(e)
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        self.config.theModal.modal('show');
        // get the template
        $.get('/ajax/modal/confirm-stop-chat', { conversation_id : conversationId },
        function(response) {
            self.config.theModal.html(response);
        });

        e.preventDefault();
    },

    stopGroupChat : function()
    {
        var self = Chat;
        var conversationId = self.config.groupChatWrapper.attr('data-conversation-id');

        self.config.messageHolder.show().find('span').text('Terminating group chat...');
        $.ajax({
            type : 'post',
            url : '/ajax/chat/stop-group-chat',
            data : {
                conversation_id : conversationId
            },
            dataType : 'json'
        }).done(function(response) {
            if(response) {
                // redirect to groups pages
                window.location.href = response.lz;
            }
        });
    }
};

Chat.init({
    fetchInterval : null,
    onlineInterval : null,

    messageHolder : $('.message-holder'),
    groupChatWrapper : $('.group-chat-wrapper'),
    chatStream : $('.chat-stream'),
    stopGroupChat : $('.stop-group-chat'),
    confirmStopGroupChat : $('#stop_group_chat'),

    messageBox : $('.message-box'),
    theModal : $('#the_modal')
});
