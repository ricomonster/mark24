<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">

        <title>eLinet | @yield('title')</title>

        <!-- Bootstrap core CSS -->
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="/assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
        <!-- Global CSS -->
        <link href="/assets/css/site/global.style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="../../assets/js/html5shiv.js"></script>
            <script src="../../assets/js/respond.min.js"></script>
        <![endif]-->
        @yield('internalCss')
        <style>
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
                        <!-- <li>
                            <a href="/planner" data-toggle="tooltip" title="Planner"
                            class="menu-items">
                                <i class="fa fa-list-alt"></i>
                            </a>
                        </li> -->
                        <li>
                            <a href="/the-forum" data-toggle="tooltip" title="The Forum"
                            class="menu-items">
                                <i class="fa fa-comments-o"></i> <span>The Forum</span>
                            </a>
                        </li>
                        <li>
                            <a href="/the-library" data-toggle="tooltip" title="The Library"
                            class="menu-items">
                                <i class="fa fa-archive"></i> <span>The Library</span>
                            </a>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <ul class="nav navbar-nav pull-right">
                        <li>
                            <a href="#" class="menu-items" data-toggle="tooltip" title="Notifications">
                                <i class="fa fa-bell-o"></i>
                            </a>
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
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="/signout">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container">
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
                    <li>Blog</li>
                    <li>Platform</li>
                    <li>Press</li>
                    <li>Help</li>
                    <li>Jobs</li>
                    <li>Terms</li>
                    <li>Privacy</li>
                    <li>Mobile</li>
                </ul>
            </div>
        </div>

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        @yield('js')
        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
            })
        </script>
        <script>
            (function($) {
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
                    var messageHolder = $('.message-holder');
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
            })(jQuery);
        </script>
    </body>
</html>
