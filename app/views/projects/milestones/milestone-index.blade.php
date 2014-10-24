@extends('layouts.master')
@section('content')
<!--Actions for phone-->
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        @if(Auth::user()->isAllowed('Delete-milestone', $milestone->user_id))
        <li><a href="{{URL::action('ProjectMilestonesController@index', array($project->id))}}" data-method="delete">Delete</a></li>
        @endif
        @if(Auth::user()->isAllowed('Edit-milestone', $milestone->user_id))
        <li><a href="{{URL::action('ProjectMilestonesController@edit', array($project->id, $milestone->id))}}">Edit</a></li>
        @endif
    </ul>
</div>

<!--info and buttons-->
<div id="project-header">
    <p class="pull-right hidden-phone">
        @if(Auth::user()->isAllowed('Delete-milestone', $milestone->user_id))
        <a href="{{URL::action('ProjectMilestonesController@index', array($project->id))}}" data-method="delete" class="btn">Delete</a>
        @endif
        @if(Auth::user()->isAllowed('Edit-milestone', $milestone->user_id))
        <a href="{{URL::action('ProjectMilestonesController@edit', array($project->id, $milestone->id))}}" class="btn btn-primary">Edit</a>
        @endif
    </p>

    <div class="page-header">
        <h4>{{$milestone->title}}</h4>
    </div>
</div>
<div class="clearfix"></div>
<!-- / info and buttons-->
<div>{{$milestone->description}}</div>
Created by: <a href="{{$milestone->user->url()}}"><span class="badge badge-info">{{$milestone->user->fullName()}}</span></a><br>

Assigned users:
<span class="pull-right muted">{{$milestone->createdAt()}}</span>
@if(!count($assignments))
(no users)
@endif
@foreach($assignments as $assignment)
<a href="{{$assignment->user->url()}}"><span class="badge">{{$assignment->user->fullName()}}</a></span>
@endforeach

@include('layouts.comments')
@stop