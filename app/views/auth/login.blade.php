<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - {{$website->slogan}}</title>
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
        <img class="center" src="img/logo.jpg">
        <hr>
        @if (count($errors))
        <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            <ul>
                @foreach ($errors as $error)
                {{ $error }}
                @endforeach
            </ul>
        </div>
        @endif

        <form  method="POST" action="{{URL::to('/')}}" accept-charset="utf-8">
            <fieldset>
                <input placeholder="Email" class="span4"  type="text" name="email" value="{{Input::old('email')}}"> <!-- username field -->
            </fieldset>
            <fieldset>
                <input placeholder="Password" class="span4" type="password" name="password"> <!-- password field -->
            </fieldset>
            <input type="hidden" name="_token" value="{{csrf_token()}}"> <!--CSRF TOKEN-->
            <label class="checkbox">
                <input type="checkbox" name="remember_me" value="true"> Remember me <!-- remember_me -->
            </label>
            <button type="submit" name="submit" class="btn btn-primary btn-block">Sign in</button> <!-- login button -->
        </form>
        <div id="forget">
        <a href="{{URL::to('password/remind')}}">Forgotten your password?</a>
        </div>
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