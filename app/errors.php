<?php //-->

/*
|--------------------------------------------------------------------------
| 404 Pages
|--------------------------------------------------------------------------
| This will handle if the user inputted malicious paths.
|
*/

App::missing(function($exception)
{
    // create a report about this event (automatically)
    return Response::view('templates.fourohfour', array(), 404);
});
