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
            .on('click', this.config.takerDetails.selector, this.showTakerDetails);
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
   }
};

QuizManager.init({
    quizId : 0,
    
    showDefault : $('.show-default'),
    takerDetails : $('.show-taker-details'),
    
    quizManagerWrapper : $('.quiz-manager'),
    defaultSection : $('.quiz-manager-default'),
    quizManagerProper : $('.quiz-manager-proper')
});