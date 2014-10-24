<nav id="profile-settings" class="span3">
	<ul class="nav nav-list well">
		<li class="nav-header">Administration menu</li>
		<li class="divider"></li>
		<li class="nav-header">Website</li>
		<li {{Request::is('admin') ? 'class="active"' : ""}}><a href="{{URL::to('admin')}}">Options</a></li>
	</ul>
</nav>