@extends('layouts.master')
@section('content')
<!--Actions for phone-->
<div class="visible-phone">
    <h5>Actions</h5>
    <ul class="nav nav-tabs nav-stacked">
        @if(Auth::user()->isAllowed('Delete-file', $file->user_id))
        <li><a href="{{URL::action('ProjectFilesController@index', array($file->id))}}" data-method="delete">Delete</a></li>
        @endif
        @if(Auth::user()->isAllowed('Edit-file', $file->user_id))
        <li><a href="{{URL::action('ProjectFilesController@edit', array($project->id, $file->id))}}">Edit</a></li>
        @endif
    </ul>
</div>

<!--info and buttons-->
<div id="project-header">
    <p class="pull-right hidden-phone">
        @if(Auth::user()->isAllowed('Delete-file', $file->user_id))
        <a href="{{URL::action('ProjectFilesController@index', array($file->id))}}" data-method="delete" class="btn">Delete</a>
        @endif
        @if(Auth::user()->isAllowed('Edit-file', $file->user_id))
        <a href="{{URL::action('ProjectFilesController@edit', array($project->id, $file->id))}}" class="btn btn-primary">Edit</a>
        @endif
    </p>
    <div class="page-header">
        <h4> {{$file->title}}</h4>
    </div>
</div>
<div class="clearfix"></div>
<!-- / info and buttons-->
<a href="{{$file->downloadUrl()}}" class="btn btn-large span3 btn-primary pull-right" type="button"><i class="icon-download icon-white"></i> Download file</a>
<div>{{$file->description}}</div>
<div class="clearfix"></div>
<span class="pull-right muted">{{$file->createdAt()}}</span>
Uploaded by:<a href="{{$file->user->url()}}"> <span class="badge badge-info">{{$file->user->fullName()}}</span></a>
<span class="muted">| File size: {{$file->megabytes()}} mb | File name: {{$file->name}}</span>
@include('layouts.comments')
@stop