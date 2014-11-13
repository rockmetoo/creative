<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <p>
            Hi {{ $email }},
        </p>
        <p>
           Thank you for subscribing with "Schooler.com". Please click <a href="{{ $confirmationLink }}" target="_blank">here</a> or paste the following link into your browser
           to confirm that you would like to receive notifications whenever we have new things:<br/>
           {{ $confirmationLink }}<br/><br/>
           The link will expire in 24 hours, so be sure to use it right away.<br/><br/>
           Thanks for using Schooler!<br/>The Schooler Team
        </p>
    </body>
</html>
