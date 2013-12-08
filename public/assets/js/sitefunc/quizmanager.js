var QuizManager = {
   init : function(config)
   {
       this.config = config;
       // catch all the events triggered
       this.bindEvents();
   },

   bindEvents : function()
   {
        $(document)
            .ready(this.crawlForQuizId)
            .on('click', this.config.showDefault.selector, this.defaultWrapper)
            .on('click', this.config.takerDetails.selector, this.showTakerDetails)
            .on('change', this.config.showType.selector, this.getTakerLists)
            .on('blur', this.config.partialScore.selector, this.setPartialScore)
            .on('click', this.config.setAnswerState.selector, this.setAnswer);
   },

   crawlForQuizId : function()
   {
       var self = QuizManager;
       self.config.quizId = self.config.quizManagerWrapper.data('quiz-id');
   },

   defaultWrapper : function(e)
   {
       var self = QuizManager;
       self.config.defaultSection.show();
       self.config.quizManagerProper.empty().hide()
       e.preventDefault();
   },

   getTakerLists : function()
   {
        var self = QuizManager;
        var $this = $(this);

        $.get('/ajax/quiz-manager/taker-lists',
            { type : $this.val(), quiz_id : $this.data('quiz-id') }, function(response) {
            // empty the content of the list holder
            // append
            self.config.listHolder.empty().append(response);
        })
   },

   showTakerDetails : function(e)
   {
       var self = QuizManager;
       var $this = $(this);

       // look for active state li
       $this.parent().parent().find('.active').removeClass('active');
       // set clicked link as active
       $this.parent().addClass('active');

       $.ajax({
           url : '/ajax/quiz-manager/show-taker-details',
           data : {
               quiz_id : self.config.quizId,
               taker_id : $this.data('user-id')
           },
           async : false
       }).done(function(response) {
           if(response.error) {

           }

           if(!response.error) {
               // hide the default wrapper
               self.config.defaultSection.hide();

               // append the response
               self.config.quizManagerProper.empty().append(response).show();
           }
       });

       e.preventDefault();
   },

   setPartialScore : function()
   {
        var self = QuizManager;
        var $this = $(this);
        var answerId = $this.attr('data-answer-id');
        var totalPoint = parseInt($this.attr('data-total-point'));
        var point = parseInt($this.val());
        var questionId = $this.attr('data-question-id');

        if(point > totalPoint) {
            self.config.messageHolder.show().find('span')
                .text('Score should not be greater than '+totalPoint);
            setTimeout(function() {
                self.config.messageHolder.hide();
            }, 3000);

            return false;
        }

        if(point <= totalPoint) {
            // send the request
            $.ajax({
                type : 'post',
                url : '/ajax/quiz-manager/set-ungraded',
                data : {
                    answer_id : answerId,
                    point : point
                },
                dataType : 'json'
            }).done(function(response) {
                if(!response.error) {
                    // change the label
                    if(point != 0) {
                        $('.tab-pane[data-question-id="'+questionId+'"]').find('.question-status')
                            .removeClass('label-info').addClass('label-success')
                            .text('Answer is correct');
                    }

                    if(point == 0) {
                        $('.tab-pane[data-question-id="'+questionId+'"]').find('.question-status')
                            .removeClass('label-info').addClass('label-danger')
                            .text('Answer is incorrect');
                    }

                    // if the score is equal to the total point, label the correct button
                    // if the score is 0, label the incorrect button
                    // update the score
                    $('.total-point-holder').find('.user-points')
                        .text(response.total_score);
                }
            })
        }
    },

    setAnswer : function(e)
    {
        var self = QuizManager;
        var element = $(this);
        var answer = element.attr('data-answer');
        var questionId = element.attr('data-question-id');
        var answerId = element.attr('data-answer-id');
        var totalPoint = element.attr('data-total-point');

        $.ajax({
            method : 'post',
            url : '/ajax/quiz-manager/set-ungraded',
            data : {
                answer_id : answerId,
                state : answer,
                total_point : totalPoint
            },
            dataType : 'json'
        }).done(function(response) {
            if(!response.error) {
                if(answer == 'correct') {
                    element.removeClass('btn-default').addClass('btn-success');
                    $('.answer-is-incorrect[data-answer-id="'+answerId+'"]')
                        .removeClass('btn-danger').addClass('btn-default');

                    // set label
                    $('.tab-pane[data-question-id="'+questionId+'"]').find('.question-status')
                        .removeClass('label-info label-danger').addClass('label-success')
                        .text('Answer is correct');

                    // put the total score in the textbox
                    $('.partial-credit[data-question-id="'+questionId+'"]').val(totalPoint);
                }

                if(answer == 'incorrect') {
                    element.removeClass('btn-default').addClass('btn-danger');
                    $('.answer-is-correct[data-answer-id="'+answerId+'"]')
                        .removeClass('btn-success').addClass('btn-default');

                    // set label
                    $('.tab-pane[data-question-id="'+questionId+'"]').find('.question-status')
                        .removeClass('label-info label-success').addClass('label-danger')
                        .text('Answer is incorrect');

                    $('.partial-credit[data-question-id="'+questionId+'"]').val('');
                }

                // update the score
                $('.total-point-holder').find('.user-points')
                    .text(response.total_score);
            }
        })

        e.preventDefault();
    }
};

QuizManager.init({
    quizId : 0,

    showDefault : $('.show-default'),
    takerDetails : $('.show-taker-details'),
    showType : $('.show-type'),

    quizManagerWrapper : $('.quiz-manager'),
    defaultSection : $('.quiz-manager-default'),
    quizManagerProper : $('.quiz-manager-proper'),
    listHolder : $('.list-holder'),
    messageHolder : $('.message-holder'),

    partialScore : $('.partial-credit'),
    setAnswerState : $('.set-answer-state')
});
