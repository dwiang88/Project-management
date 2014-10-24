@extends('layouts.master')
@section('content')

<div>

	<p class="pull-right">
		<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">
			<i class="icon-plus icon-white"></i> Invite User</a>
	</p>

	<h4 class="pull-left" style="position: relative; bottom: 5px;">User List</h4>

	<div class="clearfix"></div>
</div>

<!-- Top navigation-->
{{$users->links()}}

<div class="clearfix"></div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Label"
	 aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="Label">Invite user</h3>
	</div>
	<div class="modal-body user-invite">
		<form class="form-horizontal" method="POST" action="{{URL::to('users')}}" accept-charset="utf-8">
			<div class="control-group">
				<label class="control-label" for="input_Email">Email*:</label>

				<div class="controls">
					<input type="text" id="input_Email" name="email" placeholder="Email">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_first_name">First name*:</label>

				<div class="controls">
					<input type="text" id="input_first_name" name="first_name" placeholder="First name">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_last_name">Last name:</label>

				<div class="controls">
					<input type="text" id="input_last_name" name="last_name" placeholder="Last name">
				</div>
			</div>
		</form>
		<span class="muted">* - required fields.</span>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button type="submit" name="submit" onclick="$('.modal-body > form').submit(); " class="btn btn-primary">Invite
		</button>
	</div>
</div>
<!-- /Modal -->

<!-- USER LIST-->
<div id="user-list" class="row">
	@foreach ($users as $user)
	<div class="span6 well well-small">
		<!--Avatar-->
		<div class="pull-left">
			<a href="{{$user->url()}}"><img src="{{$user->avatarUrl()}}" class="img-polaroid" height="100px" width="100px"></a>
		</div>
		<!--/Avatar-->
		<!--Information-->
		<dl class="dl-horizontal">
			<dt>User:</dt>
			<dd><a href="{{$user->url()}}"><strong>{{$user->fullName()}}</strong></a></dd>
			<dt>Email:</dt>
			<dd><a href="#">{{$user->email}}</a></dd>
			<dt>Mobile:</dt>
			<dd>{{$user->mobile_number}}&nbsp</dd>
			@if (!empty($user->contact_type))
			<dt>{{$user->contact_type}}:</dt>
			<dd>{{$user->contact_information}}&nbsp</dd>
			@endif
			<dt>Website:</dt>
			<dd><a href="#">{{$user->website}}</a>&nbsp</dd>
		</dl>
		<!--/Information-->
		<div class="clearfix"></div>
	</div>
	@endforeach
</div>

<!-- Bottom navigation-->
{{$users->links()}}
@stop