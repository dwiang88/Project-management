@extends('layouts.master')
@section('content')
<div>
    <h4 class="pull-left" style="position: relative; bottom: 5px;">{{$user->first_name}} {{$user->last_name}}</h4>

    <div class="clearfix"></div>
</div>
<div>
<div class="row-fluid">
  	<div class="span2">
  		<div class="pull-left">
            <img src="/uploads/avatars/{{$user->avatar}}" class="img-polaroid">
        </div>
  	</div>
  	<div class="span4">
		<ul class="unstyled">
			<li>User: &nbsp<strong>{{$user->fullName()}}</strong></li>
			<li>Email: &nbsp<a href="#">{{$user->email}}</a></li>
			<li>Mobile: &nbsp{{$user->mobile_number}}</a></li>
			@if($user->contact_type)<li>{{$user->contact_type}}: &nbsp{{$user->contact_information}}</li>@endif
			<li>Website:&nbsp<a href="{{$user->website}}">{{$user->website}}</a></li>
		</ul>
		@if($user->about)
		<dl>
			<dt>About:</dt>
			<dd>{{$user->about}}&nbsp</dd>
		</dl>
		@endif
  	</div>
  	<div class="span4">
		<ul class="unstyled">
			<li>Location: &nbsp{{$user->location}}</li>
			<li>Occupation: &nbsp{{$user->occupation}}</li>
			<li>Date of Birth: &nbsp{{$user->dob}}</li>
			<li>Registered at: &nbsp{{$user->created_at}}</li>
			<li>User role:&nbsp{{$user->role->name}}</li>
		</ul>

		<div action="" class="well">
			<form method="POST">
				Change Role: &nbsp
				<select name="role">
					<option></option>
					@foreach(Role::lists('name', 'id') as $key => $role)
					<option value="{{$key}}">{{$role}}</option>
					@endforeach
				</select>
				<input type="hidden" name="_method" value="PUT" />
				<button type="submit" class="btn">Change role</button>
			</form>
		</div>
  	</div>
</div>
	@if(Auth::user()->isRole('Administrator'))

	@endif


@stop