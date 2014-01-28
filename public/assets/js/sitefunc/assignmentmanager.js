var AssignmentManager = {
    init : function(config)
    {
        this.config = config;
        this.bindEvent();
    },

    bindEvent : function()
    {
        $(document)
            .on('click', this.config.takerDetails.selector, this.showTakerDetails)
            .on('click', this.config.submitGrade.selector, this.submitResponseGrade)
            .on('click', this.config.setGrade.selector, this.submitResponseGrade);
    },

    showTakerDetails : function(e)
    {
        var self = AssignmentManager;
        var element = $(this);
        var userId = element.attr('data-user-id');
        var assignmentId = element.attr('data-assignment-id');
        var postId = element.attr('data-post-id');

        self.config.messageHolder.find('span').text('Loading...').show();
        $.ajax({
            url : '/ajax/assignment-manager/get-taker',
            data : {
                user_id         : userId,
                assignment_id   : assignmentId,
                post_id         : postId
            }
        }).done(function(response) {
            // hide the default wrapper
            self.config.defaultWrapper.hide();
            // append to the manager proper
            self.config.submissionWrapper.empty().show().append(response);
            self.config.messageHolder.hide();
        });

        e.preventDefault();
    },

    submitResponseGrade : function(e)
    {
        var self = AssignmentManager;
        var error = 0;
        var intRegex = /^\d+$/;

        var element = $(this);
        var assignmentId = element.attr('data-assignment-id');
        // get the form
        var form = $('.response-score-form[data-assignment-id="'+assignmentId+'"]');
        var userScore = form.find('.user-score');
        var assignmentScore = form.find('.total-score');

        error = 0;
        // check first if fields are empty
        // check user score
        if(userScore.val() == '' || userScore.val().length === 0) {
            userScore.parent().addClass('has-error');
            error++;
        } else if(userScore.val() != '' || userScore.val().length !== 0) {
            userScore.parent().removeClass('has-error');
            // check if the inputted data is a number
            if(!intRegex.test(userScore.val())) {
                userScore.parent().addClass('has-error');
                error++;
            }
        }

        // check the total score
        if(assignmentScore.val() == '' || assignmentScore.val().length === 0) {
            assignmentScore.parent().addClass('has-error');
            error++;
        } else if(assignmentScore.val() != '' || assignmentScore.val().length !== 0) {
            assignmentScore.parent().removeClass('has-error');
            // check if the inputted data is a number
            if(!intRegex.test(assignmentScore.val())) {
                assignmentScore.parent().addClass('has-error');
                error++;
            }
        }

        if(error === 0) {
            // fire up ajax!
            $.ajax({
                type : 'post',
                url : '/ajax/assignment-manager/set-score',
                data : form.serialize(),
                dataType : 'json'
            }).done(function(response) {
                if(!response.error) {
                    // remove the form change wrapper class
                    form.parent().empty()
                        .removeClass('no-score-set')
                        .addClass('user-total-score')
                        .append('<span class="user-score">'+response.assignment_response.score+'</span>'+
                            '<span class="seperator">/</span>'+
                            '<span class="total-score">'+response.assignment.total_score+'</span>');
                }
            })
        }

        e.preventDefault();
    }
};

AssignmentManager.init({
    defaultWrapper : $('.assignment-manager-default'),
    submissionWrapper : $('.assignment-submission-wrapper'),
    messageHolder : $('.message-holder'),
    takerDetails : $('.show-taker-details'),
    submitGrade : $('.submit-grade'),
    setGrade : $('.set-grade')
});
