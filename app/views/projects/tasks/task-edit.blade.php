@extends('layouts.master')
@section('content')

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css"/>
<div class="row-fluid">

	<legend>Edit Task
		<div class="btn-group pull-right tooltips" data-toggle="buttons-radio"
							  style="position: relative;">
			<button type="button" class="btn active"
					data-rel="tooltip" data-placement="bottom" title="Opened Tasks (unfinished)">
				<strong>Opened</strong>
			</button>
			<button type="button" class="btn"
					data-rel="tooltip" data-placement="bottom" title="Closed Tasks (finished)">
				<a href="{{URL::action('ProjectTasksController@index', array($project->id))}}?finished=true"><strong>Closed</strong></a>
			</button>
		</div>
	</legend>
	@include('layouts.errors')
	<!-- from start -->
	<form id="form" method="post" action="{{URL::action('ProjectTasksController@show', array($project->id, $task->id))}}">
		<fieldset>
			<!-- left form -->
			<div class="span6">
				<div class="control-group {{$errors->has('title')  ? 'error' : ''}}">
					<label>Task title</label>
					<input type="text" class="input-block-level" name="title" value="{{$task->title}}">
					{{$errors->first('title')}}
				</div>
				<label>Description</label>
				<textarea rows="6" name="description" class="input-block-level">{{$task->description}}</textarea>
				<label>Assign milestone</label>
				<select name="milestone">
					<option></option>
					@foreach($milestones as $milestone)
					<option data-id="{{$milestone->id}}" @if($milestone->id == $task->milestone_id) selected @endif value="{{$milestone->id}}">{{$milestone->title}}</option>
					@endforeach
				</select>
				<label>Priority</label>
				<select id="priority" class="span4" name="priority" priority="{{$task->priority}}">
					<option>Highest</option>
					<option>High</option>
					<option>Normal</option>
					<option>Low</option>
					<option>Lowest</option>
				</select>
				<div class="control-group {{$errors->has('starts')  ? 'error' : ''}}">
					<label>From</label>
					<input id="from" type="text" placeholder="Select Date" name="starts" value="{{$task->starts ? date("d-m-Y", strtotime($task->starts)): ''}}"/>

					{{$errors->first('starts')}}
				</div>
				<div class="control-group {{$errors->has('ends')  ? 'error' : ''}}">
					<label>To</label>
					<input id="to" type="text" placeholder="Select Date" name="ends" value="{{$task->ends ? date("d-m-Y", strtotime($task->ends)): ''}}"/>
					{{$errors->first('ends')}}
				</div>
				<input type="hidden" name="_method" value="put" />
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>

			<!-- right form -->
			<div class="span6">
				<label>Add users into Task</label>
				<input id="users" class="input-block-level" type="text" data-provide="typeahead" data-items="4" data-project-id="{{$project->id}}"/>
				<label>Added users</label>
				<input id="project-users" type="hidden" name="users" users="{{$assigned_users}}">
				<table class="table table-bordered">
					<tr id="first">
						<th>User infomation</th>
						<th>Remove User</th>
					</tr>
				</table>
			</div>
		</fieldset>
	</form>
	<!-- end from -->
</div>

@stop