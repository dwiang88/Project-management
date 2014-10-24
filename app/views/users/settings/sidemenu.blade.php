<nav id="profile-settings" class="span3">
    <ul class="nav nav-list well">
        <li class="nav-header">Your account</li>
        <li class="divider"></li>
        <li class="nav-header">Settings</li>
        <li {{Request::is('settings/personal', 'settings') ? 'class="active"' : ""}}><a href="{{URL::to('settings/personal')}}">Personal detail</a></li>
        <li {{Request::is('settings/contact') ? 'class="active"' : ""}}><a href="{{URL::to('settings/contact')}}">Contact detail</a></li>
        <li {{Request::is('settings/password') ? 'class="active"': ""}}><a href="{{URL::to('settings/password')}}">Password</a></li>
    </ul>
</nav>