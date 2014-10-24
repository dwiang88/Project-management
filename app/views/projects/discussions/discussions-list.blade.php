@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-thread'))
<div class="visible-phone">
	<h5>Actions</h5>
	<ul class="nav nav-tabs nav-stacked">
		<li><a href="{{URL::action('ProjectDiscussionsController@create', array($project->id))}}">New Thread</a></li>
	</ul>
</div>
@endif
<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-thread'))
	<p class="pull-right hidden-phone">
		<a href="{{URL::action('ProjectDiscussionsController@create', array($project->id))}}" class="btn btn-primary">New
			Thread</a>
	</p>
	@endif

	<div class="page-header">
		<h4> {{$project->title}} / Discussions</h4>
	</div>
</div>
<div class="clearfix"></div>

<!-- Threads -->
<div class="row">
	<div class="span3">
		<form>
			<legend>Filters</legend>
			<label class="checkbox">
				<input type="checkbox" name="my_threads" value="1" data-archived="{{$my_threads}}">Show my Threads
			</label>
			<label>
				Sort By:
			</label>
			<select class="input-block-level" name="order_by" data-oder-by="{{$order_by}}">
				<option value="date">Date Created</option>
				<option value="title">Title</option>
			</select>
			<label>
				Sort Order:
			</label>
			<select class="input-block-level" name="type" data-type="{{$type}}">
				<option value="asc">Ascending</option>
				<option value="desc">Descending</option>
			</select>
			<label>
				Search by name or description:
			</label>
			<input class="input-block-level" type="text" name="search" value="{{$search}}">
			<button type="submit" class="btn">Filter</button>
		</form>
	</div>
	<div class="span9">
		@include('layouts.errors')
		@if (Session::get('thread_created'))
		<!--Information-->
		<div class="alert alert-success ">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Well done!</strong> New thread was created.
		</div>
		@endif

		@if(!count($threads))
		<p class="lead" style="text-align: center">Currently there are no threads.</p>
		@else

		<!--Top navigation-->
		{{$threads->links()}}
		<!--/Top navigation-->
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Title</th>
				<th style="width: 70px">Author</th>
				<th style="width: 100px">Created</th>
				<th style="width: 40px;">Replies</th>
				<th style="text-align: center; width: 40px;">Actions</th>
			</tr>
			</thead>
			<tbody>
			@foreach ($threads as $thread)
			<tr>
				<td><a href="{{$thread->Url('show', $project->id)}}">{{$thread->title}}</a></td>
				<td><a href="{{$thread->user->url()}}">{{$thread->user->first_name}}</a></td>
				<td>{{$thread->created_at}}</td>
				<td>{{$thread->commentsNo()}}</td>
				<td>
					<div class="btn-group tooltips pull-right">
						@if(Auth::user()->isAllowed('Delete-thread', $thread->user_id))
						<a href="" remove-id-projects="{{$thread->id}}" class="btn btn-small" data-rel="tooltip"
						   data-placement="bottom" title="Remove">
							<i class="icon-remove"></i>
						</a>
						@endif
					</div>
				</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		@endif
		<!-- Bottom navigation-->
		{{$threads->links()}}
		<!-- /Bottom navigation-->
	</div>
</div>
<!-- / Threads -->
@stop