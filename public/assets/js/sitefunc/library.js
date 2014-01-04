(function($) {
    // views
    $(document).on('click', '.library-view', function(e) {
        var element = $(this);
        var messageHolder = $('.message-holder');
        var libraryItemsContent = $('.library-items-content');

        messageHolder.show().find('span').text('Loading...');
        $.ajax({
            url : '/ajax/thelibrary/get-library-view',
            data : { view : element.attr('data-view'), type : 'all' }
        }).done(function(response) {
            if(response) {
                // make inactive the current active view
                $('.library-view').parent().removeClass('active');
                // make current view active
                element.parent().addClass('active');
                // empty the file stream
                libraryItemsContent.empty().append(response);
                // hide message holder
                messageHolder.hide();
            }
        })

        e.preventDefault();
    })
})(jQuery);
