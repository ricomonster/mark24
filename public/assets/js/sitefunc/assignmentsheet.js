var AssignmentSheet = {
    init : function(config)
    {
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .on('click', this.config.submitResponse.selector, this.submitAssignmentResponse);
    },

    submitAssignmentResponse : function(e)
    {
        var self = AssignmentSheet;
        // check first if the content is empty
        if(self.config.assignmentResponse.val() == '' ||
        self.config.assignmentResponse.val().length === 0) {
            self.config.assignmentResponse.parent().addClass('has-error');
            return false;
        }

        // not empty
        self.config.assignmentResponse.parent().removeClass('has-error');
        // trigger ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/assignment-sheet/create-response',
            data : self.config.assignmentSheetForm.serialize(),
            dataType : 'json'
        }).done(function(data) {
            if(data) {
                // success
                // remove the form
                self.config.assignmentSheetForm.remove();
                // update the status of the assignment
                self.config.assignmentStatus.find('span')
                    .text(data.formatted_status);
                // add date of submission
                self.config.userAssignmentStatus
                    .text('Submitted on '+data.parsed_date);
                // show the response of the user
                self.config.assignmentResponseHolder.text(data.response.response).show();
            }
        });

        e.preventDefault();
    }
};

AssignmentSheet.init({
    messageHolder : $('.message-holder'),
    userAssignmentStatus : $('.user-status'),
    assignmentResponseHolder : $('.assignment-response-holder'),
    assignmentStatus : $('.assignment-status'),
    assignmentSheetForm : $('.assignment-sheet-form'),
    assignmentResponse : $('.assignment-response'),
    submitResponse : $('.submit-response')
})
