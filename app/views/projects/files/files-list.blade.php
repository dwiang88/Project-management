@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-file'))
<div class="visible-phone">
	<h5>Actions</h5>
	<ul class="nav nav-tabs nav-stacked">
		<li><a href="#myModal" role="button" data-toggle="modal">
				Upload file</a></li>
	</ul>
</div>
@endif

<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-file'))
	<p class="pull-right hidden-phone">
		<!-- Button to trigger modal -->
		<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">
			</i>Upload file</a>
	</p>
	@endif

	<div class="page-header">
		<h4> {{$project->title}} / Files</h4>
	</div>
</div>
<div class="clearfix"></div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Label"
	 aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="Label">Upload file</h3>
	</div>
	<div class="modal-body">
		<form class="form" method="POST" action="{{URL::action('ProjectFilesController@store', array($project->id))}}" accept-charset="utf-8" enctype="multipart/form-data">
			<div class="control-group">
				<label class="control-label" for="input_file">Select file:</label>

				<div class="controls">
					<input type="file" class="input-file" id="input_file" name="file">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_file_title">File title:</label>

				<div class="controls">
					<input type="text" class="input-block-level" id="input_file_title"  name="title" placeholder="File title">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_description">Description:</label>

				<div class="controls">
					<textarea rows="3" class="input-block-level" name="description" id="input_description" placeholder="Description"></textarea>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button type="submit" name="submit" onclick="$('.modal-body > form').submit();" class="btn btn-primary">Upload</button>
	</div>
</div>
<!-- /Modal -->

<!-- Files -->
<div class="row">

	<div class="span3">
		<form>
			<legend>Filters</legend>
			<label class="checkbox">
				<input type="checkbox" name="my_files" value="1" data-archived="{{$my_files}}">Show my files
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
		@if (Session::get('file_created'))
		<!--Information-->
		<div class="alert alert-success ">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Well done!</strong> New file was uploaded.
		</div>
		@endif

		@if(!count($files))
		<p class="lead" style="text-align: center">Currently there are no files.</p>
		@else
		<!--Top navigation-->
		{{$files->links()}}
		<!--/Top navigation-->
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Title</th>
				<th style="width: 70px">Author</th>
				<th style="text-align: center; width: 20px;">Type</th>
				<th style="width: 60px;">Size</th>
				<th style="width: 100px">Uploaded at</th>
				<th style="width: 40px;">Comments</th>
				<th style="text-align: center; width: 40px;">Actions</th>
			</tr>
			</thead>
			<tbody>
		@foreach ($files as $file)
			<tr>
				<td class="tooltips"><a href="{{$file->Url('show', $project->id)}}">{{$file->title}}</a>
					<a href="{{$file->downloadUrl()}}" class="btn btn-small pull-right" data-rel="tooltip" data-placement="bottom" title="Download this file">
						<i class="icon-arrow-down"></i>
					</a>
				</td>
				<td><a href="{{$file->user->url()}}">{{$file->user->first_name}}</a></td>
				<td>{{$file->mime_type}}</td>
				<td>{{$file->megabytes()}} mb</td>
				<td>{{$file->created_at}}</td>
				<td><a href="{{$file->url('show',$project->id).'#comments'}}">{{$file->commentsNo()}}</a></td>
				<td >
					<div class="btn-group tooltips pull-right">
						@if(Auth::user()->isAllowed('Edit-file', $file->user_id))
						<a  href="{{$file->Url('edit', $project->id)}}" class="btn btn-small" data-rel="tooltip" data-placement="bottom" title="Edit">
							<i class="icon-pencil"></i>
						</a>
						@endif
						@if(Auth::user()->isAllowed('Delete-file', $file->user_id))
						<a href="" remove-id-projects="{{$file->id}}" class="btn btn-small" data-rel="tooltip" data-placement="bottom" title="Remove">
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
		{{$files->links()}}
		<!-- /Bottom navigation-->
	</div>
</div>
<!-- / Files -->
@stop