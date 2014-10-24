@extends('layouts.master')
@section('content')
<!--Actions for phone-->
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        @if(Auth::user()->isAllowed('Delete-page', $page->user_id))
        <li><a href="{{URL::action('ProjectPagesController@index', array($project->id))}}" data-method="delete">Delete</a></li>
        @endif
        @if(Auth::user()->isAllowed('Edit-page', $page->user_id))
        <li><a href="{{URL::action('ProjectPagesController@edit', array($project->id, $page->id))}}">Edit</a></li>
        @endif
    </ul>
</div>

<!--Project info and buttons-->
<div id="project-header">
    <p class="pull-right hidden-phone">
        @if(Auth::user()->isAllowed('Delete-page', $page->user_id))
        <a href="{{URL::action('ProjectPagesController@index', array($project->id))}}" data-method="delete" class="btn">Delete</a>
        @endif
        @if(Auth::user()->isAllowed('Edit-page', $page->user_id))
        <a href="{{URL::action('ProjectPagesController@edit', array($project->id, $page->id))}}" class="btn btn-primary">Edit</a>
        @endif
    </p>

    <div class="page-header">
        <h4>{{$page->title}}</h4>
    </div>
</div>
<div class="clearfix"></div>
<!-- / Project info and buttons-->

<div>{{$page->content}}</div>
<span class="pull-right">{{$page->createdAt()}}</span>
Author: <a href="{{$page->user->url()}}"><span class="badge badge-info">{{$page->user->fullName()}}</span></a>

@include('layouts.comments')
@stop