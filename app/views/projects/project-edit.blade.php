@extends('layouts.master')
@section('content')


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css"/>
<div class="row-fluid">
	<legend>Edit Project</legend>
	@include('layouts.errors')
	<!-- from start -->
	<form id="form" method="post" action="{{URL::action('HomeProjectsController@update', array($project->id))}}">
		<fieldset>
			<!-- left form -->
			<div class="span6">
				<div class="control-group {{$errors->has('title')  ? 'error' : ''}}">
					<label>Project title</label>
					<input type="text" class="input-block-level" name="title" value="{{$project->title}}">
					{{$errors->first('title')}}
				</div>

				<label>Description</label>
				<textarea rows="5" name="description" class="input-block-level">{{$project->description}}</textarea>
				<label>Priority</label>
				<select id="priority" class="span4" name="priority" priority="{{$project->priority}}">
					<option>Highest</option>
					<option>High</option>
					<option>Normal</option>
					<option>Low</option>
					<option>Lowest</option>
				</select>
				<div class="control-group {{$errors->has('starts')  ? 'error' : ''}}">
					<label>From</label>
					<input id="from" type="text" placeholder="Select Date" name="starts" value="{{$project->starts ? date("d-m-Y", strtotime($project->starts)): ''}}"/>
					{{$errors->first('starts')}}
				</div>
				<div class="control-group {{$errors->has('ends')  ? 'error' : ''}}">
					<label>To</label>
					<input id="to" type="text" placeholder="Select Date" name="ends" value="{{$project->ends ? date("d-m-Y", strtotime($project->ends)): ''}}"/>
					{{$errors->first('ends')}}
				</div>
				<label class="checkbox">
					<input type="checkbox" name="visibility" value="true" {{$project->visibility == 0 ? 'checked=""' : ""}}> Private project
				</label>
				<label class="checkbox">
					<input type="checkbox" name="archived" value="true" {{$project->archived == 1 ? 'checked=""' : ""}}> Archived project
				</label>
				<input type="hidden" name="_method" value="put" />
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>

			<!-- right form -->
			<div class="span6">
				<label>Add users into Project</label>
				<input id="users" class="input-block-level" type="text" data-provide="typeahead" data-items="4"/>
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