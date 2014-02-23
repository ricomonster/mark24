<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Learn better, together.">
        <meta name="author" content="Federico A. Maglayon">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">

        <title>eLinet | Learn better, together | Sign up, Sign in</title>

        <!-- Bootstrap core CSS -->
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Global CSS -->
        <link href="/assets/css/site/global.style.css" rel="stylesheet">
        <style>
            body { padding-top: 0; }
            .header .text-muted { position: relative; }
            .main-content { min-height: 450px; padding-top: 20px; }
            /* Footer */
            .footer { font-size: 13px; margin-top: 30px; padding: 20px 0; position: relative; text-align: center; }
            /* Background Image */
            .bg {
               min-height: 100%;
               min-width: 1024px;
               width: 100%;
               height: auto;
               position: fixed;
               top: 0;
               left: 0;
               z-index: 0;
               display: none;
             }
        </style>
        @yield('css')
    </head>

    <body>
        <img src="/assets/images/splash_image.jpg" class="bg">
        <!-- Header -->
        <div class="header container">
            <ul class="nav nav-pills pull-right">
                <li><a href="/">Home</a></li>
                <li><a href="/login">Login</a></li>
                <li><a href="/signup">Sign Up</a></li>
            </ul>
            <h1 class="text-muted"><img src="/assets/images/logo.png"></h1>
        </div>

        <div class="main-content container">
            @yield('content')
        </div>

        <div class="footer">
            <ul>
                <li>
                    <strong>eLinet - eLearning Networking &copy; {{ date('Y') }}</strong>
                </li>
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

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
    </body>
</html>
