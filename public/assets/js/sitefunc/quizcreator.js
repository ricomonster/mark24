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
            .ready(this.checkActiveQuiz)
            .on('click', this.config.buttonSubmitFirstQuestion.selector, this.createNewQuiz)
            .on('click', this.config.buttonAddQuestion.selector, this.addQuestion)
            .on('click', this.config.aQuestionListItem.selector, this.selectQuestion)
            .on('change', this.config.selectQuestionType.selector, this.changeQuestionType)
            .on('blur', this.config.textareaQuestionPrompt.selector, this.changeQuestionText)
            .on('blur', this.config.textareaMultipleChoiceOption.selector, this.changeMultipleChoiceText)
            .on('click', this.config.aSetOptionCorrectAnswer.selector, this.changeCorrectOption);
    },

    // check for active quiz
    checkActiveQuiz : function() {
        var self = QuizCreator;

        $.ajax({
            url : '/ajax/quiz-creator/check-active-quiz',
        }).done(function(response) {
            if(response.active) {
                // self.config.inputQuizTimeLimit.val(quizTimeLimit);
                // hide the welcome wrapper
                self.config.welcomeMessageWrapper.hide();
                // show the quiz proper
                self.config.quizCreatorProper.show();
                // pop out the quiz item holder
                self.config.itemListWrapper.show();
                // set the global details
                self.config.quizId          = response.quiz_id;
                self.config.questionListId  = response.question_list_id;
                self.config.questionId      = response.question_id;
                self.config.questionType    = response.question_type;

                // set the question type to the first question type
                self.config.selectQuestionType.val(response.question_type);
                // load the question lists
                self.loadQuestionLists();
                // load all the questions
                self.loadQuestions();
            }
        });
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
            self.config.quizId          = response.quiz_id;
            self.config.questionListId  = response.question_list_id;
            self.config.questionId      = response.question_id;
            self.config.questionType    = response.question_type;

            // load the question lists
            self.loadQuestionLists();
            // load the first question wrapper
            self.loadQuestion();
        });
    },

    // adds a new question
    addQuestion : function(e) {
        var self = QuizCreator;
        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        var currentActiveItem       = self.config.itemListHolder.find('.active'); 
        var lastItem                = self.config.itemListHolder.find('li').last();

        self.config.messageHolder.show().find('span').text('Saving...');
                
        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/add-question',
            data    : {
                quiz_id         : self.config.quizId,
                question_type   : self.config.questionType
            },
            async   : false
        }).done(function(response) {
            // set the previous active question to hide
            currentQuestionWrapper.removeClass('active-question');

            // set inactive the current item in the question lists
            currentActiveItem.removeClass('active');

            // get last item in the list
            var itemNumber = parseInt(lastItem.find('a').text());
            // create new item
            self.config.itemListHolder
                .append(
                    '<li class="active"><a href="#" class="question-list-item" data-question-list-id="'+response.question_list_id+'"'+
                    'data-question-id="'+response.question_id+'">'
                    +(itemNumber+1)+'</a></li>');

            // set the global variables
            self.config.questionId = response.question_id;
            self.config.questionListId = response.question_list_id;

            // load the question
            self.loadQuestion();
        });

        e.preventDefault();
    },

    selectQuestion : function(e) {
        var self    = QuizCreator;
        var $this   = $(this);

        // look for the current active item and make it inactive
        self.config.itemListHolder.find('.active').removeClass('active');
        // hide the active question
        self.config.questionStreamHolder.find('.active-question')
            .removeClass('active-question');
        // make this as active item
        $this.parent().addClass('active');
        // get needed data
        self.config.questionId      = $this.data('question-id');
        self.config.questionListId  = $this.data('question-list-id');
        // show the new question
        var newActiveQuestion = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');

        newActiveQuestion.addClass('active-question');
        // get the question type
        self.config.questionType = newActiveQuestion
            .find('.question-type[data-question-id="'+self.config.questionId+'"]').val();
        // set the question type
        self.config.selectQuestionType.val(self.config.questionType);

        e.preventDefault();
    },

    // changes the question type of the question
    changeQuestionType : function() {
        var self                    = QuizCreator;
        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        
        self.config.messageHolder.show().find('span').text('Saving...');
        // update the question
        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/update-question',
            data    : {
                question_type   : self.config.selectQuestionType.val(),
                question_id     : self.config.questionId
            },
            async   : false
        }).done(function(response) {
            // delete previous response template
            currentQuestionWrapper.find('.responses-wrapper').empty();
            // load the new one
            currentQuestionWrapper.find('.responses-wrapper').append(response);
            // set the current question
            self.config.questionType = self.config.selectQuestionType.val();
            // hide the message
            self.config.messageHolder.hide();
        });
    },

    // changes the question text
    changeQuestionText : function() {
        var self    = QuizCreator;
        var $this   = $(this);

        // check first if there's a content
        if($this.val().length == 0) {
            // change border color to red
            $this.parent().addClass('has-error');
        } else if($this.val().length != 0) {
            $this.parent().removeClass('has-error');
            // show saving message
            self.config.messageHolder.show().find('span').text('Saving...');

            $.ajax({
                type    : 'post',
                url     : '/ajax/quiz-creator/update-question',
                data    : {
                    question_text : $this.val(),
                    question_id : $this.data('question-id')
                },

                dataType : 'json',
                async   : false
            }).done(function(response) {
                self.config.messageHolder.hide();
            });
        }
    },

    // changes the text in the multiple choice
    changeMultipleChoiceText : function() {
        var self    = QuizCreator;
        var $this   = $(this);
        // check if it is empty
        if($this.val().length == 0) {
            // indicate an error
            $this.parent().parent().addClass('option-error');
        } else if($this.val().length != 0) {
            // remove error indicator
            $this.parent().parent().removeClass('option-error');
            self.config.messageHolder.show().find('span').text('Saving...');

            // update row in database
            $.ajax({
                type : 'post',
                url : '/ajax/quiz-creator/update-question',
                data : {
                    multiple_choice_id  : $this.data('multiple-choice-id'),
                    choice_text         : $this.val()
                },

                dataType : 'json',
                async   : false
            }).done(function(response) {
                self.config.messageHolder.hide();
            })
        }
    },

    changeCorrectOption : function(e) {
        var self    = QuizCreator;
        var $this   = $(this);

        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        var optionTextarea = $this.parent().parent()
            .find('.option-holder').find('.multiple-choice-option');        

        // check first if textarea is not empty
        if(optionTextarea.val().length != 0) {
            self.config.messageHolder.show().find('span').text('Saving...');
            // unset first the correct option class
            // in the responses
            currentQuestionWrapper.find('.multiple-choice-response-holder')
                .find('.option-wrapper').removeClass('correct-option')
                .find('.correct-answer').removeClass('correct-answer')
                .addClass('set-as-correct-answer').text('Set as Correct Answer');

            // update
            $.ajax({
                type    : 'post',
                url     : '/ajax/quiz-creator/update-question',
                data    : {
                    multiple_choice_id : $this.data('multiple-choice-id'),
                    question_id : self.config.questionId
                },

                dataType    : 'json',
                async   : false
            }).done(function(response) {
                self.config.messageHolder.hide();

                // set the trigger to the selected one
                $this.removeClass('set-as-correct-answer').addClass('correct-answer')
                    .text('Correct Answer').parent().parent()
                    .addClass('correct-option');
            });
        } else {
            optionTextarea.parent().parent().addClass('option-error');
        }

        e.preventDefault();
    },

    // loads the question
    loadQuestion : function() {
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
    },

    // ONE TIME FUNCTIONS
    // loads the question list
    loadQuestionLists : function() {
        var self    = QuizCreator;
        
        $.ajax({
            url     : '/ajax/quiz-creator/get-question-lists',
            data    : { quiz_id : self.config.quizId },
            async   : false
        }).done(function(response) {
            // load the question lists
            self.config.itemListHolder.append(response);
        })
    },

    loadQuestions : function() {
        var self = QuizCreator;

        $.ajax({
            url     : '/ajax/quiz-creator/get-questions',
            data    : { quiz_id : self.config.quizId },
            async   : false
        }).done(function(response) {
            // append the question to the template
            self.config.questionStreamHolder.append(response);
            // get the the details of the first question
            // assign to global variables
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
    itemListHolder : $('.item-list-holder'),

    // buttons/a
    buttonSubmitFirstQuestion : $('#submit_first_question'),
    buttonAddQuestion : $('#add_question'),
    aSetOptionCorrectAnswer : $('.set-as-correct-answer'),
    aQuestionListItem : $('.question-list-item'),

    // form elements
    inputQuizTitle : $('#quiz_title'),
    inputQuizTimeLimit : $('#quiz_time_limit'),

    selectFirstQuestionType : $('#first_question_type'),
    selectQuestionType : $('#question_type'),

    textareaQuestionPrompt : $('.question-prompt'),
    textareaMultipleChoiceOption : $('.multiple-choice-option')
});
