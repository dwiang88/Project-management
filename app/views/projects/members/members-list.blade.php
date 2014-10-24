@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-member'))
<div class="visible-phone">
	<h5>Actions</h5>
	<ul class="nav nav-tabs nav-stacked">
		<li><a href="" data-method="delete"> Add/Remove Users</a></li>
	</ul>
</div>
@endif

<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-member'))
	<p class="pull-right hidden-phone">
		<!-- Button to trigger modal -->
		<a href="{{URL::action('HomeProjectsController@edit', $project->id)}}" class="btn btn-primary">
			Add/Remove Users</a>
	</p>
	@endif

	<div class="page-header">
		<h4> {{$project->title}} / Members</h4>
	</div>
</div>
<div class="clearfix"></div>


<!-- Top navigation-->
{{$assignments->links()}}

<div class="clearfix"></div>

<!-- USER LIST-->
<div id="user-list" class="row">

	@if(!count($assignments))
	<p class="lead" style="text-align: center">Currently there are no users assigned to this project.</p>
	@endif

	@foreach ($assignments as $assignment)
	<div class="span6 well well-small">
		<!--Avatar-->
		<div class="pull-left">
			<a href="{{$assignment->user->url()}}"><img src="{{$assignment->user->avatarUrl()}}" class="img-polaroid" height="100px" width="100px"></a>
		</div>
		<!--/Avatar-->
		<!--Information-->
		<dl class="dl-horizontal">
			<dt>User:</dt>
			<dd><a href="{{$assignment->user->url()}}"><strong>{{$assignment->user->fullName()}}</strong></a></dd>
			<dt>Email:</dt>
			<dd><a href="#">{{$assignment->user['email']}}</a></dd>
			<dt>Mobile:</dt>
			<dd>{{$assignment->user['mobile_number']}}&nbsp</dd>
			@if (!empty($assignment->user['contact_type']))
			<dt>{{$assignment->user['contact_type']}}:</dt>
			<dd>{{$assignment->user['contact_information']}}&nbsp</dd>
			@endif
			<dt>Website:</dt>
			<dd><a href="#">{{$assignment->user['website']}}</a>&nbsp</dd>
		</dl>
		<!--/Information-->
		<div class="clearfix"></div>
	</div>
	@endforeach
</div>

<!-- Bottom navigation-->
{{$assignments->links()}}
@stop