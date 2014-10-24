@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Create-page'))
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        <li><a href="{{URL::action('ProjectPagesController@create', array($project->id))}}">New Page</a></li>
    </ul>
</div>
@endif

<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Create-page'))
    <p class="pull-right hidden-phone">
        <a href="{{URL::action('ProjectPagesController@create', array($project->id))}}" class="btn btn-primary">New Page</a>
    </p>
    @endif

    <div class="page-header">
        <h4> {{$project->title}} / Pages</h4>
    </div>
</div>
<div class="clearfix"></div>

<!--Top navigation-->
{{$pages->links()}}
<!--/Top navigation-->

@if (Session::get('page_created'))
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
				<input type="checkbox" name="my_pages" value="1" data-archived="{{$my_pages}}">Show my pages
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
		@if(!count($pages))
		<p class="lead" style="text-align: center">Currently there are no pages.</p>
		@endif

        @foreach ($pages as $page)
        <div class="well">
			<div class="btn-group tooltips pull-right">
				@if(Auth::user()->isAllowed('Edit-page', $page->user_id))
				<button class="btn">
					<a href="{{$page->url('edit',$project->id)}}" data-rel="tooltip" data-placement="bottom" title="Edit">
						<i class="icon-pencil"></i>
					</a>
				</button>
				@endif
				@if(Auth::user()->isAllowed('Delete-page', $page->user_id))
				<button class="btn">
					<a href="" remove-id-projects="{{$page->id}}" data-rel="tooltip" data-placement="bottom" title="Remove">
						<i class="icon-remove"></i>
					</a>
				</button>
				@endif
			</div>
            <h4><a href="{{URL::action('ProjectPagesController@show', array($project->id, $page->id))}}">{{$page->title}}</a></h4>
            <!--Information-->
			<span class="muted pull-right">{{$page->createdAt()}}</span>
            <p>Author: <a href="{{$page->user->url()}}">{{$page->user->first_name}}</a>
				| Comments: <a href="{{$page->url('show',$project->id).'#comments'}}">{{$page->commentsNo()}}</a></p>
            <!--/Information-->
        </div>
        @endforeach
    </div>
</div>
<!-- / Projects -->


<!-- Bottom navigation-->
{{$pages->links()}}
<!-- /Bottom navigation-->

@stop