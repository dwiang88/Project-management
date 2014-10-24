@extends('layouts.master')
@section('content')
<div class="row">
@include('admin.sidemenu')
<div class="span9">
<!-- PERSONAL DETAIL FORM-->
<form method="POST" action="{{URL::to('admin')}}" accept-charset="utf-8" enctype="multipart/form-data"
	  class="form-horizontal well">
<fieldset>
<legend>Website options</legend>


@include('layouts.errors')
@if (Session::get('data_changed'))
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Well done!</strong> Data was successfully changed.
</div>
@endif

	<div class="control-group {{$errors->has('logo') ? 'error' : ''}}">
		<label class="control-label" for="Logo">Logo:</label>

		<div class="controls">
			<img src="/img/logo.jpg" class="img-polaroid">
			<br>
			<input class="input-file" id="logo" name="logo" type="file">
			{{$errors->first('logo')}}
			<p class="help-block">Avatar can be 400x100 px. (if the image is larger it will be changed)</p>
		</div>
	</div>

	<div class="control-group {{$errors->has('title') ? 'error' : ''}}">
		<label class="control-label" for="title">Title:</label>

		<div class="controls">
			<input class="input-xlarge" id="title" name="title" type="text" value="{{$title}}">
			{{$errors->first('title')}}
		</div>
	</div>

	<div class="control-group {{$errors->has('description') ? 'about' : ''}}">
		<label class="control-label" for="description">Description:</label>

		<div class="controls">
			<textarea class="input-block-level" id="description" name="description" rows="2">{{$description}}</textarea>
			{{$errors->first('description')}}
		</div>
	</div>

	<div class="control-group {{$errors->has('footer') ? 'error' : ''}}">
		<label class="control-label" for="footer">Footer:</label>

		<div class="controls">
			<input class="input-block-level" id="footer" name="footer" type="text" value="{{$footer}}">
			{{$errors->first('footer')}}
		</div>
	</div>

<div class="form-actions">
	<button type="submit" class="btn btn-primary">Save changes</button>
</div>
</fieldset>
</form>
</div>
</div>
@stop