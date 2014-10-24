@extends('layouts.master')
@section('content')
<div>

	@if(Auth::user()->isRole('Administrator'))
	<div class="pull-right btn-group">
		<a href="#myModal" role="button" class="btn btn-primary" data-toggle="modal">New section</a>
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			Settings
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li id="edit"><a href="edit_mode">Enable Editing</a></li>
			<li id="sort"><a href="sort_mode">Enable Sorting</a></li>
		</ul>
	</div>
	@endif

	<h4 class="pull-left" style="position: relative; bottom: 5px;">Home</h4>

	<div class="clearfix"></div>
</div>

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
                    <textarea rows="3" class="input-block-level" name="content" id="input_description" placeholder="Content"></textarea>
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
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button type="submit" name="submit" onclick="$('.modal-body > form').submit();" class="btn btn-primary">Create</button>
    </div>
</div>
<!-- /Modal -->
<!--Top dashboard part-->
<section id="top-section" class="sortable connected-sortable">
	@foreach ($top_modules as $module)
	<div id="{{ $module['id'] }}" dashboard-panel-id="{{ $module['dashboard_module']['id'] }}" class="well">
		<h4 class="muted pull-left">{{ $module['dashboard_module']['title'] }}</h4>
		
				<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
				<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
							<i class="icon-remove"></i>
				</a>
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
			
				<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
				<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
							<i class="icon-remove"></i>
				</a>
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
			
				<a href="{{ $module['dashboard_module']['id'] }}" id="dash_edit"><i class="icon-pencil pull-right"></i></a>
				<a class="pull-right" href="" id="dashboard_remove" remove-id-projects="{{ $module['id'] }}" data-rel="tooltip" data-placement="bottom" title="Remove">
							<i class="icon-remove"></i>
				</a>
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