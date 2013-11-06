<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">

        <title>Control - eLinet | @yield('title')</title>

        <!-- Bootstrap core CSS -->
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link href="/assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">
        <!-- Global CSS -->
        <!-- <link href="/assets/css/site/admin.style.css" rel="stylesheet"> -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="../../assets/js/html5shiv.js"></script>
            <script src="../../assets/js/respond.min.js"></script>
        <![endif]-->
        <style>
            body { padding-top: 60px; }
        </style>
        @yield('internalCss')
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
                    <a class="navbar-brand" href="/">Control - eLinet</a>
                </div>
                <div class="collapse navbar-collapse navigation-items">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="/control/dashboard" data-toggle="tooltip" title="Dashboard">

                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li>
                            <a href="#">Welcome, Administrator</a>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container">
            @yield('content')

            <div class="footer">
                <strong>eLinet - eLearning Networking &copy; {{ date('Y') }}</strong>
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
    </body>
</html>
