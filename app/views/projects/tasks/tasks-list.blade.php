@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-task'))
<div class="visible-phone">
	<h5>Actions</h5>
	<ul class="nav nav-tabs nav-stacked">
		<li><a href="{{URL::action('ProjectTasksController@create', array($project->id))}}">New Task</a></li>
	</ul>
</div>
@endif
<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-task'))
	<p class="pull-right hidden-phone">
		<a href="{{URL::action('ProjectTasksController@create', array($project->id))}}" class="btn btn-primary">New Task</a>
	</p>
	@endif

	<div class="page-header">
		<h4> {{$project->title}} / Tasks</h4>
	</div>
</div>
<div class="clearfix"></div>

@if (Session::get('task_created'))
<!--Information-->
<div class="alert alert-success ">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Well done!</strong> New task was created.
</div>
@endif

<!-- Tasks -->
<div class="row">
	<div class="span3">
		<form id="task_form">
			<legend>Filters</legend>
			<label class="checkbox">
				<input type="checkbox" name="my_tasks" value="1" data-archived="{{$my_tasks}}"> Show Tasks assigned to me
			</label>
			<label>
				By Milestone:
			</label>
			<select class="input-block-level" name="milestone" data-milestone="{{$input_milestone}}">
				<option></option>
				@foreach($milestones as $milestone)
				<option value="{{$milestone->id}}">{{$milestone->title}}</option>
				@endforeach
			</select>
			<label>
				Sort By:
			</label>
			<select class="input-block-level" name="order_by" data-oder-by="{{$order_by}}">
				<option value="date">Date Created</option>
				<option value="end_date">Task End Date</option>
				<option value="priority">Priority</option>
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
			<input type="hidden" name="finished" value="{{$finished}}">
			<button type="submit" class="btn">Filter</button>
		</form>
	</div>
	<div class="span9">


		<div class="btn-group pull-right tooltips" data-toggle="buttons-radio">
			<button type="button" class="btn {{$finished ? '':'active'}}" id="task_unfinished"
					data-rel="tooltip" data-placement="bottom" title="Opened Tasks (unfinished)">
				<strong>{{$tasks_left}} Opened</strong>
			</button>
			<button type="button" class="btn {{$finished ? 'active':''}}" id="task_finished"
					data-rel="tooltip" data-placement="bottom" title="Closed Tasks (finished)">
				<strong>{{$tasks_finished}} Closed</strong>
			</button>
		</div>

		@if(!count($tasks))
		<p class="lead" style="text-align: center">There are no tasks.</p>
		@else

		<!--Top navigation-->
		{{$tasks->links()}}
		<!--/Top navigation-->
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Title</th>
				<th style="width: 100px">Author</th>
				<th style="width: 70px">Priority</th>
				<th style="text-align: center; width: 80px;">Deadline</th>
				<th style="width: 40px;">Comments</th>
				<th style="text-align: center; width: 40px;">Actions</th>
			</tr>
			</thead>
			<tbody>
		@foreach ($tasks as $task)
			<tr id="{{$task->id}}">
				<td><a href="{{$task->Url('show', $project->id)}}">{{$task->title}}</a></td>
				<td><a href="{{$task->user->url()}}">{{$task->user->first_name}}</a></td>
				<td>{{$task->priority_colored()}}</td>
				<td>{{$task->deadline()}}</td>
				<td><a href="{{$task->url('show',$project->id).'#comments'}}">{{$task->commentsNo()}}</a></td>
				<td >
					<div class="btn-group tooltips pull-right">
						@if(Auth::user()->isAllowed('Edit-task', $task->user_id))
						<a href="{{$task->Url('edit', $project->id)}}" class="btn btn-small" data-rel="tooltip" data-placement="bottom" title="Edit">
								<i class="icon-pencil"></i>
						</a>
						@endif
						@if(Auth::user()->isAllowed('Delete-task', $task->user_id))
						<a  href=""  remove-id-projects="{{$task->id}}" class="btn btn-small" data-rel="tooltip" data-placement="bottom" title="Remove">
							<i class="icon-remove"></i>
						</a>
						@endif
						@if(Auth::user()->isAllowed('Edit-task', $task->user_id))
						<button task-finished-data="{{$task->id}}" class="btn btn-small" data-rel="tooltip" data-placement="bottom"
						title="{{$finished ? 'Reopen the closed Task':'Check if this task is finished'}}">
							{{$finished ? '<i class="icon-refresh"></i>':'<i class="icon-ok"></i>'}}
						</button>
						@endif
					</div>
				</td>
			</tr>
		@endforeach
			</tbody>
		</table>
		<!-- Bottom navigation-->
		{{$tasks->links()}}
		<!-- /Bottom navigation-->
		@endif
	</div>
</div>
<!-- / Tasks -->
@stop