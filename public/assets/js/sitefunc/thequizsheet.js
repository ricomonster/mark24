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
            .on('click', this.config.choiceText.selector, this.selectMultipleChoice)
            .on('click', this.config.questionItem.selector, this.changeQuestion)
            .on('click', this.config.nextButton.selector, this.changeNextQuestion)
            .on('click', this.config.previousButton.selector, this.changePreviousQuestion);
    },

    // triggers the answer of the student multiple choice
    selectMultipleChoice : function()
    {
        var self    = TheQuizSheet;
        var $this   = $(this);

        self.config.messageHolder.show().find('span').text('Saving...');

        // find first if there's an active choice
        $this.parent().parent().parent()
            .find('.choice-answer').removeClass('choice-answer');
        // set the selected choice as active
        $this.parent().parent().addClass('choice-answer');

        $.ajax({
            type : 'post',
            url : '/ajax/the-quiz-sheet/update-answer',
            data : {
                question_id : $this.data('question-id'),
                choice_id : $this.data('choice-id')
            },
            dataType : 'json'
        }).done(function(response) {

        });
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
    }
}

TheQuizSheet.init({
    questionItem : $('.question-item'),
    choiceText : $('.choice-text'),

    questionStream : $('.quiz-questions-stream'),
    questionItemsHolder : $('.question-items-holder'),
    questionNumberLabel : $('.question-number-label'),

    nextButton : $('.show-next'),
    previousButton : $('.show-previous'),
    messageHolder : $('.message-holder')
})
