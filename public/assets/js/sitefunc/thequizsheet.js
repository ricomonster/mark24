var TheQuizSheet = {
    init : function(config)
    {
        // this acts as the constructor which gets
        // all of the elements/data that the init gets
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .ready(this.checkTaker)
            .on('click', this.config.startQuiz.selector, this.startQuiz)
            .on('click', this.config.choiceText.selector, this.selectMultipleChoice)
            .on('click', this.config.trueFalseAnswer.selector, this.trueFalseQuestion)
            .on('click', this.config.questionItem.selector, this.changeQuestion)
            .on('click', this.config.nextButton.selector, this.changeNextQuestion)
            .on('click', this.config.previousButton.selector, this.changePreviousQuestion)
            .on('blur', this.config.shortAnswerText.selector, this.shortAnswer);
    },

    // checks if the current user already took the quiz
    checkTaker : function()
    {
        var self    = TheQuizSheet;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Loading...');

        $.ajax({
            url : '/ajax/the-quiz-sheet/check-quiz-taker',
            data : {
                quiz_id : self.config.theQuizSheet.data('quiz-id')
            },
            dataType : 'json'
        }).done(function(response) {
            if(response.taken) {
                self.config.welcomeWrapper.hide();
                // show the quiz sheet proper
                self.config.theQuizSheetProper.show();

                self.config.quizTakerId = response.details.quiz_taker_id;

                // load the questions
                self.loadQuestions();
            }

            self.config.messageHolder.hide();
        })
    },

    // starts the quiz
    startQuiz : function(e)
    {
        var self    = TheQuizSheet;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Loading...');

        // triggers ajax call to start the quiz
        $.ajax({
            type : 'post',
            url : '/ajax/the-quiz-sheet/start-quiz',
            data : {
                quiz_id : $this.data('quiz-id')
            },
            dataType : 'json',
            async : false
        }).done(function(response) {
            // hide the welcome wrapper
            self.config.welcomeWrapper.hide();
            // show the quiz sheet proper
            self.config.theQuizSheetProper.show();
            self.config.quizTakerId = response.taker_id;

            // load the questions
            self.loadQuestions();
        })

        e.preventDefault();
    },

    // triggers the answer of the student multiple choice
    selectMultipleChoice : function()
    {
        var self    = TheQuizSheet;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Updating...');

        // find first if there's an active choice
        $this.parent().parent().parent()
            .find('.choice-answer').removeClass('choice-answer');
        // set the selected choice as active
        $this.parent().parent().addClass('choice-answer');
        $.ajax({
            type : 'post',
            url : '/ajax/the-quiz-sheet/update-answer',
            data : {
                quiz_taker_id   : self.config.quizTakerId,
                question_id     : $this.data('question-id'),
                choice_id       : $this.data('choice-id')
            },
            dataType : 'json'
        }).done(function(response) {
            self.config.messageHolder.hide();
        });
    },

    // triggers the true or false response
    trueFalseQuestion : function(e)
    {
        var self = TheQuizSheet;
        var $this = $(this);
        var questionId = $this.data('question-id');

        self.config.messageHolder.show().find('span').text('Updating...');
        // if there's an already selected answer
        // unset it first
        $('.response-true-false[data-question-id="'+questionId+'"]').find('.true-false-answer')
            .removeClass('btn-success').addClass('btn-default');

        // trigger the ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/the-quiz-sheet/update-answer',
            data : {
                quiz_taker_id   : self.config.quizTakerId,
                question_id     : questionId,
                true_false      : $this.data('answer')
            },
            dataType : 'json'
        }).done(function(response) {
            // set the clicked button to success state
            $this.removeClass('btn-default').addClass('btn-success');
            self.config.messageHolder.hide();
        });

        e.preventDefault();
    },

    // short answer
    shortAnswer : function()
    {
        var self        = TheQuizSheet;
        var $this       = $(this);
        var questionId  = $this.data('question-id');

        if($this.val() == '' || $this.val().length == 0) {
            // add state class
        }

        if($this.val() != '' || $this.val().length != 0) {
            self.config.messageHolder.show().find('span').text('Updating...');

            // trigger ajax
            $.ajax({
                type : 'post',
                url : '/ajax/the-quiz-sheet/update-answer',
                data : {
                    quiz_taker_id   : self.config.quizTakerId,
                    question_id     : questionId,
                    short_answer    : $this.val()
                }
            }).done(function(response) {
                self.config.messageHolder.hide()
            });
        }
    },

    // changes the question shown
    changeQuestion : function(e)
    {
        var self            = TheQuizSheet;
        var $this           = $(this);
        var questionList    = $this.data('question-list-id');

        self.config.messageHolder.show().find('span').text('Loading...');
        // set first to inactive state all active items
        $this.parent().parent().find('.active').removeClass('active');
        // set current item to active state
        $this.parent().addClass('active');
        // hide first the current active question
        self.config.questionStream.find('.show-question').removeClass('show-question');
        // show the question
        self.config.questionStream.find('.question-holder[data-question-list-id="'+questionList+'"]')
            .addClass('show-question');
        // update the question number
        self.config.questionNumberLabel.text('Question '+parseInt($this.text()));

        self.validateNavigation();
        self.config.messageHolder.hide();

        e.preventDefault();
    },

    // selects and shows the next question
    changeNextQuestion : function()
    {
        var self = TheQuizSheet;
        var $this = $(this);

        self.config.messageHolder.show().find('span').text('Loading...');
        // get the active item in the list and the necessary details
        var activeItem = self.config.questionItemsHolder.find('.active');
        var activeQuestionListId = activeItem.find('a').data('question-list-id');
        // get the next item and the necessary details
        var nextItem = activeItem.next();
        var nextQuestionListId = nextItem.find('a').data('question-list-id');

        // hide and set to inactive the active items
        activeItem.removeClass('active');
        self.config.questionStream.find('.show-question').removeClass('show-question');

        // unhide and set to active the next question / item
        nextItem.addClass('active');
        self.config.questionStream
            .find('.question-holder[data-question-list-id="'+nextQuestionListId+'"]')
            .addClass('show-question');

        // update the question number
        self.config.questionNumberLabel.text('Question '+parseInt(nextItem.find('a').text()));

        self.validateNavigation();
        self.config.messageHolder.hide();

    },

    // selects and shows the previous question
    changePreviousQuestion : function()
    {
        var self = TheQuizSheet;
        var $this = $(this);

        self.config.messageHolder.show().find('span').text('Loading...');
        // get the active item in the list and the necessary details
        var activeItem = self.config.questionItemsHolder.find('.active');
        var activeQuestionListId = activeItem.find('a').data('question-list-id');
        // get the next item and the necessary details
        var previousItem = activeItem.prev();
        var previousQuestionListId = previousItem.find('a').data('question-list-id');

        // hide and set to inactive the active items
        activeItem.removeClass('active');
        self.config.questionStream.find('.show-question').removeClass('show-question');

        // unhide and set to active the next question / item
        previousItem.addClass('active');
        self.config.questionStream
            .find('.question-holder[data-question-list-id="'+previousQuestionListId+'"]')
            .addClass('show-question');

        // update the question number
        self.config.questionNumberLabel.text('Question '+parseInt(previousItem.find('a').text()));

        self.validateNavigation();
        self.config.messageHolder.hide();
    },

    // checks if the current question is the first or last one
    // this will also indicate the state of the navigation buttons
    validateNavigation : function()
    {
        var self = TheQuizSheet;
        var listHolder = $('.question-items-holder');
        var firstChild = listHolder.find('.active').is(':first-child');
        var lastChild = listHolder.find('.active').is(':last-child');

        if(firstChild) {
            self.config.previousButton.attr('disabled', 'disabled');
            self.config.nextButton.removeAttr('disabled');
            return false;
        } else if(lastChild) {
            self.config.previousButton.removeAttr('disabled');
            self.config.nextButton.attr('disabled', 'disabled');
            return false;
        } else if(!lastChild || !firstChild) {
            self.config.previousButton.removeAttr('disabled');
            self.config.nextButton.removeAttr('disabled');
            return false;
        }
    },

    // loads the questions
    loadQuestions : function()
    {
        var self = TheQuizSheet;

        $.ajax({
            url : '/ajax/the-quiz-sheet/get-questions',
            data : {
                quiz_id         : self.config.theQuizSheet.data('quiz-id'),
                quiz_taker_id   : self.config.quizTakerId
            },
            async : false
        }).done(function(response) {
            // load the question
            self.config.questionStream.append(response);
            self.config.messageHolder.hide();
        })
    }
}

TheQuizSheet.init({
    quizTakerId : 0,

    startQuiz : $('.start-quiz'),
    questionItem : $('.question-item'),
    choiceText : $('.choice-text'),
    trueFalseAnswer : $('.true-false-answer'),
    shortAnswerText : $('.short-answer-text'),

    theQuizSheet : $('.the-quiz-sheet'),
    welcomeWrapper : $('.welcome-quiz-sheet-wrapper'),
    theQuizSheetProper : $('.the-quiz-sheet-proper'),

    questionStream : $('.quiz-questions-stream'),
    questionItemsHolder : $('.question-items-holder'),
    questionNumberLabel : $('.question-number-label'),

    nextButton : $('.show-next'),
    previousButton : $('.show-previous'),
    messageHolder : $('.message-holder')
})
