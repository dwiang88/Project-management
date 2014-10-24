<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset Password - {{$website->slogan}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/css/login.css" rel="stylesheet">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script type="text/javascript">
    </script>

</head>
<body>

<div class="row">
    <div id="login-form" class="span4 center well">
        <legend>Change your password</legend>

        @if (Session::has('error'))
        <!-- Error-->
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ trans(Session::get('reason')) }}
        </div>
        @endif

        <form  method="POST" action="{{URL::to('password/reset/'.$token)}}" accept-charset="utf-8">
            <fieldset>
                <label>New password:</label>
                <input placeholder="New password" type="password" name="password" class="span4">
            </fieldset>
            <fieldset>
                <label>Confirm new password:</label>
                <input placeholder="Password confirmation" type="password" name="password_confirmation" class="span4">
            </fieldset>
            <input type="hidden" name="token" value="{{ $token }}"> <!--RESET TOKEN-->
            <input type="hidden" name="_token" value="{{ csrf_token() }}"> <!--CSRF TOKEN-->
            <button type="submit" name="submit" class="btn btn-primary btn-block">Change</button> <!-- login button -->
        </form>
    </div>

    <footer class="center">
        <p>{{$website->footer}}</p>
    </footer>
</div>

<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-1.8.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>