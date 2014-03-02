<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div class="message">
            @if($accountType == 1)
            <p>You're so close to having an eLinet account and start preaching knowledge!</p>

            <p>Before you can continue to access our site, you will need to confirm
            first your email. Please click the link the below to confirm you email and
            continue on learning with fun and connecting with your colleagues and students.</p>
            @else
            <p>You're so close to having an eLinet account and enjoy learning.</p>

            <p>Before you can continue to access our site, you will need to confirm
            first your email. Please click the link the below to confirm you email and
            continue on learning with fun and connecting with your teachers and classmates.</p>
            @endif

            <a href="{{ Request::root().'/confirm?email_address='.urlencode($email).
            '&code_id='.$confirmationCode.'&key='.$code }}" target="_blank">
                {{ Request::root().'/confirm?email_address='.urlencode($email).'&key='.$code }}
            </a>

            <p>Good luck and enjoy learning!</p>
            <strong>from the eLinet Team</strong>
        </div>
    </body>
</html>
