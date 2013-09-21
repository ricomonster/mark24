// Quiz Creator version 4.x
var QuizCreator = {
    init : function(config) {
        // this acts as the constructor which gets
        // all of the elements/data that the init gets
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function() {
        // all of event triggers are here
        // it will fire the certain function that the 
        // event has on it
        $(document)
            .on('click', this.config.buttonSubmitFirstQuestion.selector, this.createNewQuiz)
            .on('change', this.config.selectQuestionType.selector, this.changeQuestionType);
    },

    // creates a new quiz and creates the first question
    createNewQuiz : function() {
        var self            = QuizCreator;
        var quizTimeLimit   = 0;

        self.config.messageHolder.show().find('span').text('Saving...');
        // check if quiz time limit is empty.
        // if empty, set the time limit to 1 hour or 60 minutes
        if(self.config.inputQuizTimeLimit.val().length == 0) {
            // set time limit to 60 minutes
            quizTimeLimit = 60;
        } else {
            quizTimeLimit = self.config.inputQuizTimeLimit.val();
        }

        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/create-new-quiz',
            data    : {
                quiz_title      : self.config.inputQuizTitle.val(),
                quiz_time_limit : quizTimeLimit,
                question_type   : self.config.selectFirstQuestionType.val()
            },

            dataType : 'json',
            async   : false
        }).done(function(response) {
            self.config.inputQuizTimeLimit.val(quizTimeLimit);
            // hide the welcome wrapper
            self.config.welcomeMessageWrapper.hide();
            // show the quiz proper
            self.config.quizCreatorProper.show();
            // pop out the quiz item holder
            self.config.itemListWrapper.show();
            // set the global details
            self.config.quizId = response.quiz_id;
            self.config.questionListId = response.question_list_id;
            self.config.questionId = response.question_id;
            self.config.questionType = self.config.selectFirstQuestionType.val();

            // load the first question wrapper
            self.loadFirstQuestion();
        });
    },

    // changes the question type of the question
    changeQuestionType : function() {
        var self                    = QuizCreator;
        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        
        self.config.messageHolder.show().find('span').text('Saving...');
        // update the question
        $.ajax({
            type : 'post',
            url : '/ajax/quiz-creator/update-question',
            data : {
                question_type   : self.config.selectQuestionType.val(),
                question_id     : self.config.questionId
            }
        }).done(function(response) {
            // delete previous response template
            currentQuestionWrapper.find('.responses-wrapper').empty();
            // load the new one
            currentQuestionWrapper.find('.responses-wrapper').append(response);
            // hide the message
            self.config.messageHolder.hide();
        });
    },

    // loads the first question
    // applicable only to first time quiz makers
    loadFirstQuestion : function() {
        var self = QuizCreator;

        $.ajax({
            url : '/ajax/quiz-creator/get-question',
            data : {
                quiz_id             : self.config.quizId,
                question_list_id    : self.config.questionListId
            }
        }).done(function(response) {
            // append the question to the template
            self.config.questionStreamHolder.append(response);
            // hide message holder
            self.config.messageHolder.hide();
        })
    }
}

QuizCreator.init({
    // get all the elements we need!
    // global
    quizId : 0,
    questionListId : 0,
    questionId : 0,
    questionType : '',
    // div/span/p/strong/
    messageHolder : $('.message-holder'),

    welcomeMessageWrapper : $('.quiz-creator-welcome-wrapper'),
    itemListWrapper : $('.item-list-wrapper'),
    quizCreatorProper : $('.quiz-creator-proper'),

    questionStreamHolder : $('.question-stream-holder'),
    // buttons
    buttonSubmitFirstQuestion : $('#submit_first_question'),
    // form elements
    inputQuizTitle : $('#quiz_title'),
    inputQuizTimeLimit : $('#quiz_time_limit'),

    selectFirstQuestionType : $('#first_question_type'),
    selectQuestionType : $('#question_type')
});
