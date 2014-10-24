@extends('layouts.master')
@section('content')
<!--Actions for phone-->
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
    	@if(Auth::user()->isAllowed('Create-module'))
        <li><a href="#myModal" role="button" data-toggle="modal">New section</a></li>
        @endif
        @if(Auth::user()->isAllowed('Delete-project', $project->user_id))
        <li><a href="{{$project->url('destroy')}}" data-method="delete">Delete Project</a></li>
        @endif
		@if(Auth::user()->isAllowed('Edit-project', $project->user_id))
		<li id="sort">
			<a href="{{$project->url('edit')}}" >Edit Project</a>
		</li>
		@endif
        @if(Auth::user()->isAllowed('Edit-module'))
		<li id="edit"><a href="edit_mode">Enable Dashboard Editing</a></li>
		@endif
    </ul>
</div>

<!--Project info and buttons-->
<div class="project-header">
	<div class="pull-right btn-toolbar hidden-phone" style="position: relative; bottom: 13px;">
		@if(Auth::user()->isAllowed('Create-module'))
		<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">New section</a>
		@endif

		@if(Auth::user()->isAllowed('Edit-project', $project->user_id) OR Auth::user()->isAllowed('Delete-project', $project->user_id))
		<div class="btn-group">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				Project Settings
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@if(Auth::user()->isAllowed('Delete-project', $project->user_id))
				<li>
					<a href="{{$project->url('destroy')}}" data-method="delete">Delete Project</a>
				</li>
				@endif
				@if(Auth::user()->isAllowed('Edit-project', $project->user_id))
				<li id="sort">
					<a href="{{$project->url('edit')}}">Edit Project</a>
				</li>
				@endif
			</ul>
		</div>
		@endif

		@if(Auth::user()->isAllowed('Edit-module'))
		<div class="btn-group">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				Dashboard Settings
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li id="edit"><a href="edit_mode">Enable Editing</a></li>
				<li id="sort"><a href="sort_mode">Enable Sorting</a></li>
			</ul>
		</div>
		@endif
	</div>

	<div class="page-header" style="margin: 0 0 15px 0">
		<h4>{{$project->title}}</h4>
	</div>

</div>
<div class="clearfix"></div>

<!-- / Project info and buttons-->

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Label"
	 aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="Label">Create section</h3>
	</div>
	<div class="modal-body">
		<form class="form" method="POST" action="{{URL::to('dashboard/new_module')}}" accept-charset="utf-8">
			<div class="control-group">
				<label class="control-label" for="input_file_title">Section title:</label>

				<div class="controls">
					<input type="text" class="input-block-level" id="input_file_title"  name="title" placeholder="Section title">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_description">Content:</label>

				<div class="controls">
					<textarea rows="3" name="content" class="input-block-level" id="input_description" placeholder="Content"></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="input_place">Select location:</label>
				<div class="controls">
					<select id="input_place" name="location">
						<option>Top</option>
						<option>Right</option>
						<option>Left</option>
					</select>
				</div>
			</div>
			<input type="hidden" value="{{$project->id}}" name="project_id">
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button type="submit" name="submit" onclick="$('.modal-body > form').submit();" class="btn btn-primary">Create</button>
	</div>
</div>
<!-- /Modal -->

@if(empty($top_modules) AND empty($left_modlues) AND empty($right_modules))
<p class="lead" style="text-align: center">Currently there are no sections.</p>
@endif

<!--Top dashboard part-->
<section id="top-section" class="sortable connected-sortable">
	@foreach ($top_modules as $module)
	<div id="{{ $module['id'] }}" dashboard-panel-id="{{ $module['dashboard_module']['id'] }}" class="well">
		<h4 class="muted pull-left">{{ $module['dashboard_module']['title'] }}</h4>
		@if(Auth::user()->isAllowed('Edit-module'))
		<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
		@endif
		@if(Auth::user()->isAllowed('Delete-module'))
		<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
			<i class="icon-remove"></i>
		</a>
		@endif
		<div class="clearfix"></div>
		<div id="dashboard_text">
			{{ $module['dashboard_module']['content'] }}
		</div>
	</div>
	@endforeach
</section>
<!-- / Top dashboard part-->

<div class="row" data-dashboard="null">
	<!-- Left menu part-->
	<section id="left-section" class="span6 sortable connected-sortable">
		@foreach ($left_modules as $module)
		<div id="{{ $module['id'] }}" dashboard-panel-id="{{ $module['dashboard_module']['id'] }}" class="well">
			<h4 class="muted pull-left">{{ $module['dashboard_module']['title'] }}</h4>

			@if(Auth::user()->isAllowed('Edit-module'))
			<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
			@endif
			@if(Auth::user()->isAllowed('Delete-module'))
			<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
				<i class="icon-remove"></i>
			</a>
			@endif
			<div class="clearfix"></div>
			<div id="dashboard_text">
				{{ $module['dashboard_module']['content']}}
			</div>
		</div>
		@endforeach
	</section>
	<!-- / Left menu part-->

	<!--Right menu part-->
	<section id="right-section" class="span6  sortable connected-sortable">
		@foreach ($right_modules as $module)
		<div id="{{ $module['id'] }}" dashboard-panel-id="{{ $module['dashboard_module']['id'] }}" class="well">
			<h4 class="muted pull-left">{{ $module['dashboard_module']['title'] }}</h4>

			@if(Auth::user()->isAllowed('Edit-module'))
			<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
			@endif
			@if(Auth::user()->isAllowed('Delete-module'))
			<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
				<i class="icon-remove"></i>
			</a>
			@endif
			<div class="clearfix"></div>
			<div id="dashboard_text">
				{{ $module['dashboard_module']['content']}}
			</div>
		</div>
		@endforeach
	</section>
	<!--/Right menu part-->
</div>
@stop