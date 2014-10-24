@extends('layouts.master')
@section('content')

<div class="project-header">
    @if(Auth::user()->isAllowed('Create-project'))
    <div style="position: relative; bottom: 10px" class="pull-right btn-group">
        <a href="{{URL::to('projects/create')}}" role="button" class="btn btn-primary" data-toggle="modal">
        <i class="icon-plus icon-white"></i> Create Project</a>
    </div>
    @endif

	<div class="page-header">
		<h4 style="margin: 0; position: relative; bottom: 5px">Projects</h4>

	</div>
    <div class="clearfix"></div>

</div>


@if(Session::get('project_created'))
<!--Information-->
<div class="alert alert-success ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Well done!</strong> New project was created.
</div>
@endif

<!-- Projects -->
<div class="row">
    <div class="span3">
        <form>
			<legend>Filters</legend>
            <label class="checkbox">
                <input type="checkbox" name="my_projects" value="1" data-archived="{{$my_projects}}"> Show Projects assigned to me
            </label>
            <label class="checkbox">
                <input type="checkbox" name="archived" value="1" data-archived="{{$archived}}"> Show archived Projects
            </label>
            @if(Auth::user()->isAllowed('See-private-projects'))
            <label class="checkbox">
                <input type="checkbox" name="visibility" value="1" data-visibility="{{$visibility}}"> Show only private Projects
            </label>
            @endif
            <label>
                Sort By:
            </label>
            <select class="input-block-level" name="order_by" data-oder-by="{{$order_by}}">
                <option value="date">Date Created</option>
                <option value="end_date">Project End Date</option>
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
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>
    <div class="span9">
        <!--Top navigation-->
        {{$projects->links()}}
        <!--/Top navigation-->
        @foreach ($projects as $project)
        <div class="well" data-priority="{{$project->priority}}">
			<div class="btn-group tooltips pull-right">
				@if(Auth::user()->isAllowed('Edit-project', $project->user_id))
					<a class="btn" href="{{$project->url('edit')}}" data-rel="tooltip" data-placement="bottom" title="Edit">
						<i class="icon-pencil"></i>
					</a>
                @endif
                @if(Auth::user()->isAllowed('Delete-project', $project->user_id))
					<a class="btn" href="" remove-id-projects="{{$project->id}}" data-rel="tooltip" data-placement="bottom" title="Remove">
						<i class="icon-remove"></i>
					</a>
                @endif
			</div>
            <h4><a href="{{$project->url('show')}}">{{$project->title}}</a></h4>
            <!--Information-->

            <p>{{$project->description}}</p>
            <p>Priority: {{$project->priority_colored()}}<span class="pull-right muted">{{$project->deadline()}}</span></p>
			<div class="clearfix"></div>
			<div class="row-fluid  tooltips">
				<div class="span6">
					<p class="pull-left">Tasks: &nbsp</p>
					<a href="{{URL::to('projects/'.$project->id.'/tasks')}}">
						<div class="progress progress-success" data-rel="tooltip" data-placement="bottom" title="{{$project->task_left}} Tasks left to complete this project">
							<div class="bar" style="width: {{$project->task_percent}}%">
								{{$project->task_finished}}/{{$project->task_total}}</div>
						</div>
					</a>
				</div>
				<div class="span6 pull-right">
					<p class="pull-left  pull-right">Days: &nbsp</p>
					<div class="progress" data-rel="tooltip" data-placement="bottom" title="{{$project->days_left}} Days left to finnish this project">
						<div class="bar" style="width: {{$project->time_left_perc}}%;">
							{{$project->days}}</div>
					</div>
				</div>
			</div>
            <!--/Information-->
        </div>
        @endforeach
        <!-- Bottom navigation-->
        {{$projects->links()}}
        <!-- /Bottom navigation-->
    </div>
</div>
<!-- / Projects -->



@stop