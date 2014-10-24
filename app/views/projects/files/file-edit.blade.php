@extends('layouts.master')
@section('content')
<div class="row-fluid">
	<legend>Edit File</legend>
	@include('layouts.errors')
	<form class="form-horizontal" method="POST" action="{{URL::action('ProjectFilesController@update', array($project->id, $file->id))}}" accept-charset="utf-8" enctype="multipart/form-data">
		<fieldset>
			<div class="control-group">
				<label>File title</label>
				<input type="text" class="input-block-level" name="title" value="{{$file->title}}">
			</div>
			<label>Description</label>
			<textarea rows="8" name="description" class="input-block-level">{{$file->description}}</textarea>
			<br>
			<div class="control-group {{$errors->has('file')  ? 'error' : ''}}">
				<label>Select file: <span class="muted">Current file name: {{$file->name}}</span> </label>
				<input type="file" class="input-file" name="file">
				{{$errors->first('file')}}
			</div>
			<input type="hidden" name="_method" value="put" />
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
	</form>
	<!-- end from -->
</div>

@stop