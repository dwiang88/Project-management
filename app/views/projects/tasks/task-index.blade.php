@extends('layouts.master')
@section('content')
<!--Actions for phone-->
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        @if(Auth::user()->isAllowed('Delete-task', $task->user_id))
        <li><a href="{{URL::action('ProjectTasksController@index', array($project->id))}}" data-method="delete">Delete</a></li>
        @endif
        @if(Auth::user()->isAllowed('Edit-task', $task->user_id))
        <li><a href="{{URL::action('ProjectTasksController@edit', array($project->id, $task->id))}}">Edit</a></li>
        @endif
    </ul>
</div>

<!--Project info and buttons-->
<div id="project-header">
    <div class="pull-right hidden-phone" style="position: relative; bottom: 8px;">

		<div class="btn-group tooltips" data-toggle="buttons-radio">
			@if(Auth::user()->isAllowed('Edit-task', $task->user_id))
			<button id="task-finished-indside-data" type="button" class="btn {{$task->finished == 1 ? '':'active'}}"
					data-rel="tooltip" data-placement="bottom" title="Opened Task (unfinished)">
				<strong>Opened</strong>
			</button>
			<button id="task-finished-indside-data" type="button" class="btn {{$task->finished == 1 ? 'active':''}}"
					data-rel="tooltip" data-placement="bottom" title="Closed Task (finished)">
				<strong>Closed</strong>
			</button>
			@endif
		</div>

		@if(Auth::user()->isAllowed('Delete-task', $task->user_id))
		<a href="{{URL::action('ProjectTasksController@index', array($project->id))}}" data-method="delete" class="btn">Delete</a>
		@endif
		@if(Auth::user()->isAllowed('Edit-task', $task->user_id))
		<a href="{{URL::action('ProjectTasksController@edit', array($project->id, $task->id))}}" class="btn btn-primary">Edit</a>
		@endif
	</div>

    <div class="page-header">
        <h4>{{$task->title}}</h4>
    </div>
</div>
<div class="clearfix"></div>
<!-- / Project info and buttons-->
<div>{{$task->description}}</div>
Created by: <a href="{{$creator->url()}}"><span class="badge badge-info">{{$creator->fullName()}}</span></a>
| Priority: {{$task->priority_colored()}} | Deadline: {{$task->deadline() ? $task->deadline(): '(no deadline)'}}
<br>
Assigned users to this task:
<span class="pull-right muted">{{$task->createdAt()}}</span>
@if(!count($assigned_users))
(no users)
@endif
@foreach($assigned_users as $assignment)
<a href="{{$assignment->user->url()}}"><span class="badge">{{$assignment->user->fullName()}}</span></a>
@endforeach

@include('layouts.comments')
@stop