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
            .on('click', this.config.buttonAddResponse.selector, this.addResponse)
            .on('click', this.config.buttonAssignQuiz.selector, this.assignQuiz)
            .on('click', this.config.buttonRemoveQuestion.selector, this.removeQuestion)
            .on('click', this.config.aRemoveOption.selector, this.removeMultipleChoiceAnswer)
            .on('change', this.config.selectQuestionType.selector, this.changeQuestionType)
            .on('change', this.config.selectTrueFalseOption.selector, this.changeTrueFalseAnswer)
            .on('blur', this.config.inputQuizTitle.selector, this.updateQuizTitle)
            .on('blur', this.config.textareaQuestionPrompt.selector, this.changeQuestionText)
            .on('blur', this.config.textareaMultipleChoiceOption.selector, this.changeMultipleChoiceText)
            .on('blur', this.config.inputQuestionPoint.selector, this.updateQuestionPoint)
            .on('click', this.config.aSetOptionCorrectAnswer.selector, this.changeCorrectOption);
    },

    // check for active quiz
    checkActiveQuiz : function() {
        var self = QuizCreator;

        self.config.messageHolder.show().find('span').text('Loading...');
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
                self.config.questionCount   = response.question_count;

                // show the quiz title
                self.config.inputQuizTitle.val(response.quiz_title);

                // show the quiz time limit
                self.config.inputQuizTimeLimit.val(response.quiz_time_limit);

                // show the remove question button if 2 or more questions
                if(self.config.questionCount != 1) {
                    self.config.buttonRemoveQuestion.show();
                }

                // set the question type to the first question type
                self.config.selectQuestionType.val(response.question_type);
                // set the question point
                self.config.inputQuestionPoint.val(response.question_point);
                // load the question lists
                self.loadQuestionLists();
                // load all the questions
                self.loadQuestions();
            }

            self.config.messageHolder.hide();
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
            self.config.questionCount   = 1;

            // load the question lists
            self.loadQuestionLists();
            // load the first question wrapper
            self.loadQuestion();
        });
    },

    // updates the title of the quiz
    updateQuizTitle : function() {
        var self    = QuizCreator;
        var $this   = $(this);

        if($this.val() != '' && $this.val().lenght != 0) {
            // trigger ajax call
            $.ajax({
                type        : 'post',
                url         : '/ajax/quiz-creator/update-quiz',
                data        : {
                    quiz_id : self.config.quizId,
                    title   : $this.val()
                },
                dataType    : 'json',
                async       : false
            }).done(function(response) {

            })
        }
    },

    // updates the time limit of the quiz
    updateQuizTimeLimit : function() {

    },

    // updates the description of the quiz
    updateQuizDescription : function() {

    },

    updateQuestionPoint : function() {
        var self                    = QuizCreator;
        var $this                   = $(this);
        var totalQuestionPoint      = 0;
        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');

        // check first if the value is either 0 or empty
        if($this.val() != 0 && $this.val().length != 0) {
            self.config.messageHolder.show().find('span').text('Saving...');

            // trigger ajax
            $.ajax({
                type        : 'post',
                url         : '/ajax/quiz-creator/update-question',
                data        : {
                    question_id     : self.config.questionId,
                    question_point  : $this.val()
                },

                dataType    : 'json',
                async       : false
            }).done(function(response) {
                // update the hidden input for the question point
                var questionPoint = currentQuestionWrapper
                    .find('.question-point[data-question-id="'+self.config.questionId+'"]')
                    .val($this.val());

                // count the total question point
                $('.question-point').each(function() {
                    totalQuestionPoint += parseInt($(this).val());
                });

                // show the total question point
                console.log('Total Question Point: ' + totalQuestionPoint);

                // hide message holder
                self.config.messageHolder.hide();
            });
        }
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
            self.config.questionId      = response.question_id;
            self.config.questionListId  = response.question_list_id;
            self.config.questionCount   += 1;

            // show the remove question button if 2 or more questions
            if(self.config.questionCount != 1) {
                self.config.buttonRemoveQuestion.show();
            }

            // load the question
            self.loadQuestion();
        });

        e.preventDefault();
    },

    selectQuestion : function(e) {
        var self    = QuizCreator;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Saving...');

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
        // get the question point
        var questionPoint = newActiveQuestion
            .find('.question-point[data-question-id="'+self.config.questionId+'"]').val();
        // set the question type
        self.config.selectQuestionType.val(self.config.questionType);
        // set the question point
        self.config.inputQuestionPoint.val(questionPoint);

        self.config.messageHolder.hide();

        // prototype point counter
        var t = 0;
        $('.question-point').each(function() {
            t += parseInt($(this).val());
        });
        console.log(t);

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
            currentQuestionWrapper.find('.responses-wrapper')
                .append(response);
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
                    question_text   : $this.val(),
                    question_id     : $this.data('question-id')
                },

                dataType    : 'json',
                async       : false
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
                type    : 'post',
                url     : '/ajax/quiz-creator/update-question',
                data    : {
                    multiple_choice_id  : $this.data('multiple-choice-id'),
                    choice_text         : $this.val()
                },

                dataType    : 'json',
                async       : false
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

            currentQuestionWrapper.find('.multiple-choice-response-holder')
                .find('.option-wrapper').find('.remove-answer').show();

            // update
            $.ajax({
                type    : 'post',
                url     : '/ajax/quiz-creator/update-question',
                data    : {
                    multiple_choice_id  : $this.data('multiple-choice-id'),
                    question_id         : self.config.questionId
                },

                dataType    : 'json',
                async       : false
            }).done(function(response) {
                // look for a remove-answer class and hide it
                $this.parent().find('.remove-answer').hide();
                // set the trigger to the selected one
                $this.removeClass('set-as-correct-answer').addClass('correct-answer')
                    .text('Correct Answer').parent().parent()
                    .addClass('correct-option');

                self.config.messageHolder.hide();
            });
        } else {
            optionTextarea.parent().parent().addClass('option-error');
        }

        e.preventDefault();
    },

    // changes the answer of a true or false question
    changeTrueFalseAnswer : function() {
        var self    = QuizCreator;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Saving...');

        // update choice
        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/update-question',
            data    : {
                true_false_id   : $this.data('true-false-id'),
                answer          : $this.val()
            },
            dataType    : 'json',
            async       : false
        }).done(function(response) {
            self.config.messageHolder.hide();
        })
    },

    // adds a multiple choice response
    addResponse : function(e) {
        var self    = QuizCreator;
        var $this   = $(this);
        var currentQuestionWrapper = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        // get the last option
        var lastOption = currentQuestionWrapper.find('.responses-wrapper')
            .find('.multiple-choice-response-holder').find('.option-wrapper:last-child')
            .find('.choice-letter');

        var newOptionLetter = String.fromCharCode(lastOption.text().charCodeAt(0) + 1);

        self.config.messageHolder.show().find('span').text('Saving...');

        // add a response and get the template
        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/add-response',
            data    : {
                question_id : $this.data('question-id')
            },
            async   : false
        }).done(function(response) {
            // append the new response
            currentQuestionWrapper.find('.responses-wrapper')
                .find('.multiple-choice-response-holder').append(response)
                .find('.option-wrapper:last-child').find('.choice-letter')
                .text(newOptionLetter);

            self.config.messageHolder.hide();
        });

        e.preventDefault();
    },

    // removes a multiple choice response
    removeMultipleChoiceAnswer : function(e) {
        var self    = QuizCreator;
        var $this   = $(this);

        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/update-question',
            data    : {
                multiple_choice_id :$this.data('multiple-choice-id')
            },
            dataType : 'json',
            async : false
        }).done(function(response) {
            if(!response.error) {
                if(!$this.parent().parent().is(':last-child') && !$this.parent().parent().is(':first-child')) {
                    // update first the letters next options
                    var toRemoveOption = $this.parent().parent();
                    toRemoveOption.nextAll('.option-wrapper').each(function() {
                        $(this).find('.choice-letter').text(
                            String.fromCharCode($(this).find('.choice-letter').text().charCodeAt(0) - 1));
                    });
                }

                $this.parent().parent().remove();
            }
        });

        e.preventDefault();
    },

    // removes question
    removeQuestion : function(e) {
        var self = QuizCreator;
        // get current/active question details/elements
        var currentQuestionWrapper  = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');
        var currentQuestionItemList = $('.question-list-item[data-question-id="'+self.config.questionId+'"]');

        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/remove-question',
            data    : {
                quiz_id             : self.config.quizId,
                question_id         : self.config.questionId,
                question_list_id    : self.config.questionListId
            },
            dataType : 'json',
            async   : false
        }).done(function(response) {
            if(currentQuestionItemList.parent().is(':first-child') ||
                (!currentQuestionItemList.parent().is(':first-child') &&
                !currentQuestionItemList.parent().is(':last-child'))) {
                // loop the next item lists
                currentQuestionItemList.parent().nextAll().each(function() {
                    // get the item number
                    $(this).children().text(
                        parseInt($(this).children().text()) - 1);
                });

                // set active the next item
                var newActiveItem = currentQuestionItemList.parent().next();
                newActiveItem.addClass('active');
            } else if(currentQuestionItemList.parent().is(':last-child')) {
                // set active the previous item
                var newActiveItem = currentQuestionItemList.parent().prev();
                newActiveItem.addClass('active');
            }

            // remove current question and question item
            currentQuestionItemList.parent().remove();
            currentQuestionWrapper.remove();

            // set the question_id and question_list_id
            self.config.questionId      = newActiveItem.find('.question-list-item').data('question-id');
            self.config.questionListId  = newActiveItem.find('.question-list-item').data('question-list-id');
            self.config.questionCount   -= 1;

            // get the question details
            // show the new question
            var newActiveQuestion = $('.question-wrapper[data-question-id="'+self.config.questionId+'"]');

            newActiveQuestion.addClass('active-question');
            // get the question type
            self.config.questionType = newActiveQuestion
                .find('.question-type[data-question-id="'+self.config.questionId+'"]').val();
            // get the question point
            var questionPoint = newActiveQuestion
                .find('.question-point[data-question-id="'+self.config.questionId+'"]').val();
            // set the question type
            self.config.selectQuestionType.val(self.config.questionType);
            // set the question point
            self.config.inputQuestionPoint.val(questionPoint);

            console.log(self.config.questionCount);
            // count the number of items
            // hide the remove question button if items are just one
            if(self.config.questionCount == 1) {
                self.config.buttonRemoveQuestion.hide();
            }

            // remove the element
        });

        e.preventDefault();
    },

    // assigns the quiz
    assignQuiz : function(e) {
        var self                = QuizCreator;
        var $this               = $(this);
        var errorCounter        = 0;
        var totalQuestionPoint  = 0;

        self.config.messageHolder.show().find('span').text('Saving...');
        self.config.topMessageHolder.slideUp(400);

        errorCounter = 0;

        // check for question text that are empty
        $('.question-prompt').each(function() {
            var questionId = $(this).data('question-id');
            if($(this).val() == '' && $(this).val().length == 0) {
                // highlight the textarea
                $(this).parent().addClass('has-error');
                // highlight the question list number
                $('.question-list-item[data-question-id="'+questionId+'"]')
                    .parent().addClass('has-error');
                // increment errorCounter
                errorCounter++;
            }

            if($(this).val() != '' && $(this).val().length != 0) {
                $(this).parent().removeClass('has-error');
                $('.question-list-item[data-question-id="'+questionId+'"]')
                    .parent().removeClass('has-error');
            }
        });

        // check for option text if there are empty
        $('.multiple-choice-option').each(function() {
            var questionId = $(this).data('question-id');
            if($(this).val() == '' && $(this).val().length == 0) {
                // highlight textarera
                $(this).parent().parent().addClass('option-error');
                // highlight the question list number
                $('.question-list-item[data-question-id="'+questionId+'"]')
                    .parent().addClass('has-error');
                // increment errorCounter
                errorCounter++;
            }

            if($(this).val() != '' && $(this).val().length != 0) {
                // highlight textarera
                $(this).parent().parent().removeClass('option-error');
                // highlight the question list number
                $('.question-list-item[data-question-id="'+questionId+'"]')
                    .parent().removeClass('has-error');
            }
        });

        // check if there are errors
        if(errorCounter == 0) {
            // count the total question point
            $('.question-point').each(function() {
                totalQuestionPoint += parseInt($(this).val());
            });

            // ajax call
            $.ajax({
                type : 'post',
                url : '/ajax/quiz-creator/submit-quiz',
                data : {
                    quiz_id : self.config.quizId,
                    totalScore : totalQuestionPoint
                },
                dataType : 'json',
                async : false
            }).done(function(response) {
                if(!response.error) {
                    window.location.href = response.lz;
                }
            })

            return false;
        }

        // there are errors
        if(errorCounter != 0) {
            // show an alert message
            self.config.topMessageHolder.text('There are errors. Please fix before continuing...')
                .hide().slideDown(400);
            self.config.messageHolder.hide();
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
        var totalQuestionPoint = 0;

        $.ajax({
            url     : '/ajax/quiz-creator/get-questions',
            data    : { quiz_id : self.config.quizId },
            async   : false
        }).done(function(response) {
            // append the question to the template
            self.config.questionStreamHolder.append(response);

            // count all question point
            $('.question-point').each(function() {
                totalQuestionPoint += parseInt($(this).val());
            });

            // show the total question point
            console.log('Total Question Point: ' + totalQuestionPoint);

            // hide message holder
            self.config.messageHolder.hide();
        })
    }
}

QuizCreator.init({
    // get all the elements we need!
    // global
    quizId          : 0,
    questionListId  : 0,
    questionId      : 0,
    questionCount   : 0,
    questionType    : null,

    // div/span/p/strong/
    messageHolder                   : $('.message-holder'),

    welcomeMessageWrapper           : $('.quiz-creator-welcome-wrapper'),
    itemListWrapper                 : $('.item-list-wrapper'),
    quizCreatorProper               : $('.quiz-creator-proper'),

    questionStreamHolder            : $('.question-stream-holder'),
    itemListHolder                  : $('.item-list-holder'),

    topMessageHolder                : $('.top-message-holder'),

    // buttons/a
    buttonSubmitFirstQuestion       : $('#submit_first_question'),
    buttonRemoveQuestion            : $('.remove-question'),
    buttonAddQuestion               : $('#add_question'),
    buttonAddResponse               : $('.add-response'),
    aSetOptionCorrectAnswer         : $('.set-as-correct-answer'),
    aQuestionListItem               : $('.question-list-item'),
    aRemoveOption                   : $('.remove-option'),
    buttonAssignQuiz                : $('.assign-quiz'),

    // form elements
    inputQuizTitle                  : $('#quiz_title'),
    inputQuizTimeLimit              : $('#quiz_time_limit'),
    inputQuestionPoint              : $('#question_point'),

    selectFirstQuestionType         : $('#first_question_type'),
    selectQuestionType              : $('#question_type'),
    selectTrueFalseOption           : $('.true-false-option'),

    textareaQuestionPrompt          : $('.question-prompt'),
    textareaMultipleChoiceOption    : $('.multiple-choice-option')
});
