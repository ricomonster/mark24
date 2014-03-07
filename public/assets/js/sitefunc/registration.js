var Registration = {
    init : function(config)
    {
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function()
    {
        $(document)
            .on('click', this.config.studentSubmitButton.selector, this.studentSignup)
            .on('submit', this.config.studentSignupForm.selector, this.studentSignup)
            .on('click', this.config.teacherSubmitButton.selector, this.teacherSignup)
            .on('submit', this.config.teacherSignupForm.selector, this.teacherSignup);
    },

    studentSignup : function(e)
    {
        var self = Registration;
        self.config.studentSignupForm.find('.form-group')
            .removeClass('has-error').find('.alert').remove();
        // trigger ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/users/validate-student',
            data : self.config.studentSignupForm.serialize(),
            dataType : 'json'
        }).done(function(response) {
            if(response.error) {
                $.each(response.messages, function(i, message) {
                    $('.'+i).parent().addClass('has-error')
                        .prepend('<span class="help-block text-danger">'+message+'</span>');
                });
            }

            if(!response.error) {
                window.location.href = response.lz;
                return false;
            }
        })

        e.preventDefault();
    },

    teacherSignup : function(e)
    {
        var self = Registration;
        self.config.teacherSignupForm.find('.form-group')
            .removeClass('has-error').find('.alert').remove();
        // trigger ajax call
        $.ajax({
            type : 'post',
            url : '/ajax/users/validate-teacher',
            data : self.config.teacherSignupForm.serialize(),
            dataType : 'json'
        }).done(function(response) {
            if(response.error) {
                $.each(response.messages, function(i, message) {
                    $('.'+i).parent().addClass('has-error')
                        .prepend('<span class="help-block text-danger">'+message+'</span>');
                });
            }

            if(!response.error) {
                window.location.href = response.lz;
                return false;
            }
        })

        e.preventDefault();
    }
};

Registration.init({
    teacherSignupForm : $('#teacher_signup_form'),
    teacherSubmitButton : $('#teacher_signup_button'),
    studentSignupForm : $('#student_signup_form'),
    studentSubmitButton : $('#student_signup_button')
})
