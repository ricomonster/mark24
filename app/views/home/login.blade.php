<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/assets/ico/favicon.png">

        <title>eLinet | Where Learning Happens | Sign up, Sign in</title>

        <!-- Bootstrap core CSS -->
        <link href="/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Global CSS -->
        <link href="/assets/css/site/global.style.css" rel="stylesheet">
        <style>
            body { padding-top: 0; }
            .header .text-muted { position: relative; }
            .main-content { padding-top: 20px; }
            .left-panel,
            .right-panel { width: 350px; margin: auto !important; }
            .left-panel p { font-size: 20px; }

            /* Right Panel */
            .right-panel {
                background: rgb(245, 245, 245);
                background: rgba(245, 245, 245, 0.7);
                min-height: 350px;
            }

            /* Signin */
            .signin-form .section-title { font-size: 18px; }
            .signin-form form { padding-top: 20px; }
            .signin-form .signup-options { padding-top: 20px; text-align: center; }
            /* Teacher Signup */
            .teacher-signup-form { display: none; }
            .teacher-signup-form .section-title { font-size: 18px; }
            .teacher-signup-form form { padding-top: 20px; }
            /* Student Signup */
            .student-signup-form { display: none; }
            .student-signup-form .section-title { font-size: 18px; }
            .student-signup-form form { padding-top: 20px; }
            /* Footer */
            .footer { font-size: 11px; margin-top: 30px; padding: 20px 0; position: relative; text-align: center; }
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
    </head>

    <body>
        <img src="/assets/images/test-splash.gif" class="bg">
        <!-- Header -->
        <div class="header container">
            <h1 class="text-muted">eLinet</h1>
        </div>

        <div class="main-content container">
            <div class="row">
                <div class="col-md-6">
                    <div class="left-panel">
                        <h1>Welcome to eLinet</h1>
                        <p>
                            eLinet helps connect all learners with the people
                            and resources needed to reach their full potential
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-panel well">
                        <div class="signin-form">
                            <div class="section-title">Sign in to eLinet.</div>
                            {{ Form::open(array('url'=>'users/validate_signin', 'autocomplete' => 'off')) }}
                                @if(isset($loginError))
                                <div class="alert alert-danger">{{ $loginError }}</div>
                                @endif

                                <div class="form-group">
                                    <input type="text" name="signin-username-email" class="form-control"
                                    id="signin_username_email" placeholder="Username or Email">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="signin-password" class="form-control"
                                    id="signin_password" placeholder="Password">
                                </div>
                                <div class="form-buttons">
                                    <button type="submit" class="btn btn-default pull-right">Login</button>
                                </div>
                                <div class="clearfix"></div>
                            {{ Form::close() }}

                            <hr />

                            <div class="section-title"><strong>Sign up now.</strong> It's free.</div>
                            <div class="signup-options">
                                <button class="btn btn-primary" id="show_teacher_form">I'm a Teacher</button>
                                <button class="btn btn-primary" id="show_student_form">I'm a Student</button>
                            </div>
                        </div>

                        <div class="teacher-signup-form">
                            <div class="section-title">Teacher Sign Up</div>
                            {{ Form::open(array('url'=>'users/validate_teacher_signup', 'autocomplete' => 'off')) }}
                                <div class="form-group">
                                    <select name="teacher-title" id="teacher_title" class="form-control">
                                        <option value="">Select Title:</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Mrs.">Mrs.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Dr.">Dr.</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="teacher-firstname" id="teacher_firstname"
                                    class="form-control" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="teacher-lastname"
                                    id="teacher_lastname" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="teacher-username" id="teacher_username"
                                    class="form-control" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="teacher-email" id="teacher_email"
                                    class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="teacher-password" id="teacher_password"
                                    class="form-control" placeholder="Password">
                                </div>
                                <div class="form-buttons">
                                    <button type="submit" class="btn btn-primary" id="teacher_signup_button">Sign up</button>
                                    <button class="btn btn-default" id="teacher_signup_cancel">Cancel</button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="student-signup-form">
                            <div class="section-title">Student Sign Up</div>
                            {{ Form::open(array('url'=>'users/validate_student_signup', 'autocomplete' => 'off')) }}
                                <div class="form-group">
                                    <input type="text" name="student-group-code" id="student_group_code"
                                    class="form-control" placeholder="Group Code">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="student-username" id="student_username"
                                    class="form-control" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="student-password" id="student_password"
                                    class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="student-email" id="student_email"
                                    class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="student-firstname" id="student_firstname"
                                    class="form-control" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="student-lastname"
                                    id="student_lastname" placeholder="Last Name">
                                </div>
                                <div class="form-buttons">
                                    <button type="submit" class="btn btn-primary" id="student_signup_button">Sign up</button>
                                    <button class="btn btn-default" id="student_signup_cancel">Cancel</button>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            eLinet &copy; {{ date('Y') }}
        </div>

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script>
            (function($) {
                var signinForm  = $('.signin-form');
                var teacherForm = $('.teacher-signup-form');
                var studentForm = $('.student-signup-form');

                $('#show_teacher_form').on('click', function() {
                    signinForm.slideUp();
                    setTimeout(function() {
                        teacherForm.slideDown();
                    }, 600);

                    return false;
                });

                $('#show_student_form').on('click', function() {
                    signinForm.slideUp();
                    setTimeout(function() {
                        studentForm.slideDown();
                    }, 600);

                    return false;
                });

                $('#teacher_signup_cancel').on('click', function(e) {
                    teacherForm.slideUp();
                    setTimeout(function() {
                        signinForm.slideDown();
                    }, 600);

                    e.preventDefault();
                });

                $('#student_signup_cancel').on('click', function(e) {
                    studentForm.slideUp();
                    setTimeout(function() {
                        signinForm.slideDown();
                    }, 600);

                    e.preventDefault();
                });

                $(document).ready(function() {
                    $('.bg').load(function() {
                        $(this).fadeIn(1000);
                    });
                })
            })(jQuery);
        </script>
    </body>
</html>
