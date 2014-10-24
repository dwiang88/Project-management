<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>Dear {{$first_name}},</p>

<p>You were invited to the project management system {{URL::to('/')}}. Now you can login using this email address
    and password bellow (please change it as soon as you login):<br><br>
    <strong>Your password:</strong> <em>{{ $password }}</em>
</p>

<div>
    Best regards,<br>
    Company name.
</div>
</body>
</html>