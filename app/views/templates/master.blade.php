<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Learn better, together.">
        <meta name="author" content="Federico A. Maglayon">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">

        <title>eLinet | @yield('title')</title>

        <!-- Bootstrap core CSS -->
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="/assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
        <!-- Global CSS -->
        <link href="/assets/css/site/global.style.css" rel="stylesheet">
        <!-- Selectize CSS -->
        <link href="/assets/css/plugins/selectize.bootstrap3.css" rel="stylesheet">
        @yield('internalCss')
        <style>
            .public-service-message { display: none; }
            .public-service-message p { font-size: 16px; }
            .public-service-message small { font-size: 13px; }
        </style>
    </head>

    <body>

        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="/"></a>
                </div>
                <div class="collapse navbar-collapse navigation-items">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="/home" data-toggle="tooltip" title="Home"
                            class="menu-items">
                                <i class="fa fa-home"></i> <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="/the-forum" data-toggle="tooltip" title="The Forum"
                            class="menu-items">
                                <i class="fa fa-comments-o"></i> <span>The Forum</span>
                            </a>
                        </li>
                        @if(Auth::user()->account_type == 1)
                        <li>
                            <a href="/the-library" data-toggle="tooltip" title="The Library"
                            class="menu-items">
                                <i class="fa fa-archive"></i> <span>The Library</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_bar"
                            placeholder="Search">
                        </div>
                    </form>
                    <ul class="nav navbar-nav pull-right right-navbar">
                        <li class="dropdown notification-dropdown">
                            <a href="#" role="button" data-toggle="dropdown"
                            class="dropdown-toggle menu-items fetch-notifications">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-danger notification-count"></span>
                            </a>
                            <ul class="notification-stream dropdown-menu"
                            role="menu" aria-labelledby="drop1">
                                <li class="see-all">
                                    <a href="/notifications">
                                        <i class="fa fa-list-ul"></i> See all notifications
                                    </a>
                                </li>
                                <!-- <li class="spinner">Spinner</li> -->
                            </ul>
                        </li>
                        <li class="dropdown nav-profile-avatar">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                {{ Helper::avatar(30, "small", "header-avatar") }}
                                Me <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="/profile/{{ Auth::user()->username }}">Profile</a>
                                </li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="/settings">Settings</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#"
                                class="show-report-problem">Report a Problem</a></li>
                                @if(Auth::user()->flag == 0)
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                href="/control/">Control</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                href="/control/choose-account-type">Change Account Type</a></li>
                                @endif
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="/signout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container ultimate-mega-container">
            {{ Helper::confirmAccount() }}

            <div class="message-holder"><span></span></div>
            <div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
            aria-labelledby="the_modal_label" aria-hidden="true"></div>

            <blockquote class="public-service-message alert alert-warning">
                <p><strong>Welcome to eLinet!</strong></p>
                <p>Sorry for the incovenience due to the site is still in beta stage.
                In case you've found some errors or problems kindly
                <a href="#" class="show-report-problem">report</a> it.</p>
                <small>eLinet Team</small>
            </blockquote>

            @yield('content')

            <div class="footer">
                <strong>eLinet - eLearning Networking &copy; {{ date('Y') }}</strong>
                <ul>
                    <li>About</li>
                    <li>Help</li>
                    <li>Terms</li>
                    <li>Privacy</li>
                </ul>
            </div>
        </div>

        {{ Auxillary::groupChats() }}

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/plugins/selectize.min.js"></script>
        @yield('js')
        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
                // selectize
                $('#search_bar').selectize({
                    valueField: 'url',
                    labelField: 'content',
                    searchField: ['content'],
                    maxOptions: 10,
                    options: [],
                    create: false,
                    render: {
                        option: function(item, escape) {
                            return '<div class="search-result"><a href="'+item.url+'">'+item.icon+escape(item.content)+'</a></div>';
                        }
                    },
                    optgroups: [
                        {value: 'users', label: 'Users'},
                        {value: 'forums', label: 'Forums'}
                    ],
                    optgroupField: 'class',
                    optgroupOrder: ['users','posts'],
                    load: function(query, callback) {
                        if (!query.length) return callback();
                            $.ajax({
                                url: '/ajax/search',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    q: query
                                },
                                error: function() {
                                    callback();
                                },
                                success: function(res) {
                                    callback(res.data);
                                }
                            });
                        },
                    onChange: function(){
                        window.location = this.items[0];
                    }
                });
            });
        </script>
        <script>
            (function($) {
                var notificationCounter = $('.notification-count');
                var messageHolder = $('.message-holder');
                // first to trigger on every page load to check for notifications
                fetchNotifications();
                var notificationInverval = setInterval(function() {
                    fetchNotifications();
                }, 30000);

                $(document).on('click', '.show-report-problem', function(e) {
                    var modal = $('#the_modal');
                    modal.modal('show');
                    $.ajax({
                        url : '/ajax/modal/get-report-form'
                    }).done(function(response) {
                        if(response) {
                            modal.html(response);
                        }
                    })
                });

                $(document).on('click', '#submit_problem', function(e) {
                    var location = window.location;
                    var modal = $('#the_modal');
                    var form = $('.report-problem-form');
                    var problem = $('textarea[name="problem"]');

                    // check first if the textarea has content
                    if(problem.val() == '' || problem.val().length == 0) {
                        problem.parent().addClass('has-error');
                    } else if(problem.val() != '' || problem.val().length != 0) {
                        problem.parent().removeClass('has-error');
                        messageHolder.show().find('span').text('Sending report...');
                        $.ajax({
                            type : 'post',
                            url : '/ajax/modal/submit-problem',
                            data : {
                                problem : problem.val(),
                                location : location.pathname
                            },
                            dataType : 'json'
                        }).done(function(response) {
                            if(response.error) {
                                messageHolder.show().find('span')
                                    .text('There is a problem with your request. Please try again later');
                            }

                            if(!response.error) {
                                messageHolder.show().find('span')
                                    .text('Thank for your input. We will check on it and do some actions.');
                                modal.modal('hide');
                            }

                            setTimeout(function() { messageHolder.fadeOut() }, 5000);
                        })
                    }

                    e.preventDefault();
                });

                $(document).on('click', '.fetch-notifications', function(e) {
                    var notificationStream = $('.notification-stream');
                    var notificationCounter = $('.notification-count');
                    // empty the contents
                    notificationStream.contents(':not(.spinner, .see-all)').remove();
                    // fetch data
                    $.ajax({
                        url : '/ajax/notifications/fetch'
                    }).done(function(response) {
                        if(response) {
                            notificationStream.prepend(response)
                                // .find('.spinner').hide();
                            notificationCounter.fadeOut(400);
                            notificationCounter.parent().delay(400)
                                .animate({ width: '54px' }, 350);
                            var title = document.title;
                            title = title.split(" (");
                            document.title = title[0];

                        }
                    });

                    e.preventDefault();
                });

                function fetchNotifications()
                {
                    $.ajax({
                        url : '/ajax/notifications/check'
                    }).done(function(response) {
                        if(response.count) {
                            notificationCounter.parent().animate({ width: '85px' }, 350);
                            notificationCounter.text(response.count).delay(400)
                                .animate({ width: 'toggle' }, 400);
                            var title = document.title;
                            title = title.split(" (");
                            document.title = title[0]+' ('+response.count+')';
                        }
                    });
                }

                $('.send-confirmation').on('click', function(e) {
                    e.preventDefault();
                    messageHolder.show().find('span')
                        .text('Processing your request...');

                    $.ajax({
                        type : 'post',
                        url : '/ajax/users/send-confirmation-mail',
                        dataType : 'json'
                    }).done(function(response) {
                        if (!response.error) {
                            messageHolder.fadeOut();
                            // change content
                            $('.confirm-account').empty()
                                .text('Please check your email! We already sent the confirmation mail.');
                            setTimeout(function() {
                                $('.confirm-account').slideUp();
                            }, 5000);
                        }
                    });
                });
            })(jQuery);
        </script>
    </body>
</html>
