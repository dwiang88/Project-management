@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-milestone'))
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        <li><a href="{{URL::action('ProjectMilestonesController@create', array($project->id))}}">New Milestone</a></li>
    </ul>
</div>
@endif

<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-milestone'))
    <p class="pull-right hidden-phone">
        <a href="{{URL::action('ProjectMilestonesController@create', array($project->id))}}" class="btn btn-primary">New Milestone</a>
    </p>
	@endif


    <div class="page-header">
        <h4> {{$project->title}} / Milestones</h4>
    </div>
</div>
<div class="clearfix"></div>

@if (Session::get('milestone_created'))
<!--Information-->
<div class="alert alert-success ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Well done!</strong> New milestone was created.
</div>
@endif

<!-- Milestones -->
<div class="row">
    <div class="span3">
		<form>
			<legend>Filters</legend>
			<label class="checkbox">
				<input type="checkbox" name="my_tasks" value="1" data-archived="{{$my_milestones}}"> Show Milestones assigned to me
			</label>
			<label class="checkbox">
				<input type="checkbox" name="archived" value="1" data-archived="{{$archived}}"> Show finished Milestones
			</label>
			<label>
				Sort By:
			</label>
			<select class="input-block-level" name="order_by" data-oder-by="{{$order_by}}">
				<option value="date">Date Created</option>
				<option value="end_date">Milestone End Date</option>
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

		@if(!count($milestones))
		<p class="lead" style="text-align: center">Currently there are no milestones.</p>
		@endif

		<!--Top navigation-->
		{{$milestones->links()}}
		<!--/Top navigation-->
        @foreach ($milestones as $milestone)
        <div class="well" data-priority="{{$milestone->priority}}">
			<div class="btn-group tooltips pull-right">
				@if(Auth::user()->isAllowed('Edit-milestone', $milestone->user_id))
					<a href="{{$milestone->url('edit',$project->id)}}" class="btn" data-rel="tooltip" data-placement="bottom" title="Edit">
						<i class="icon-pencil"></i>
					</a>
				@endif
				@if(Auth::user()->isAllowed('Delete-milestone', $milestone->user_id))
					<a class="btn" href="" remove-id-projects="{{$milestone->id}}" data-rel="tooltip" data-placement="bottom" title="Remove">
						<i class="icon-remove"></i>
					</a>
				@endif
			</div>
            <h4><a href="{{$milestone->url('show',$project->id)}}">{{$milestone->title}}</a></h4>
            <!--Information-->
			<p>
				Priority: {{$milestone->priority_colored()}}
				| Author: <a href="{{$milestone->user->url()}}">{{$milestone->user->first_name}}</a>
				| Comments: <a href="{{$milestone->url('show',$project->id).'#comments'}}">{{$milestone->commentsNo()}}</a>
				<br>
				Assigned users:
				@if(!count($milestone->assignments))
				(no users)
				@endif
				@foreach($milestone->assignments as $assignment)
				<a href="{{$assignment->user->url()}}"><span class="badge">{{$assignment->user->fullName()}}</a></span>
				@endforeach
				<span class="pull-right muted">{{$milestone->deadline()}}</span>
			</p>
			<div class="clearfix"></div>
			<div class="row-fluid  tooltips">
				<div class="span6">
					<p class="pull-left">Tasks: &nbsp</p>
					<a href="{{URL::to('projects/'.Request::segment(2).'/tasks?milestone='.$milestone->id)}}">
						<div class="progress progress-success" data-rel="tooltip" data-placement="bottom" title="{{$milestone->task_left}} Tasks left to complete this Milestone">
							<div class="bar" style="width: {{$milestone->task_percent}}%">
								{{$milestone->task_finished}}/{{$milestone->task_total}}</div>
						</div>
					</a>
				</div>
				<div class="span6 pull-right">
					<p class="pull-left  pull-right">Days: &nbsp</p>
					<div class="progress" data-rel="tooltip" data-placement="bottom" title="{{$milestone->days_left}} Days left to finnish this Milestone">
						<div class="bar" style="width: {{$milestone->time_left_perc}}%;">
							{{$milestone->days}}</div>
					</div>
				</div>
			</div>
            <!--/Information-->
        </div>
        @endforeach
		<!-- Bottom navigation-->
		{{$milestones->links()}}
		<!-- /Bottom navigation-->
    </div>
</div>
<!-- / Milestones -->
@stop