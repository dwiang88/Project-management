@extends('layouts.master')
@section('content')
<!--Actions for phone-->
@if(Auth::user()->isAllowed('Delete-thread', $thread->user_id))
<div class="visible-phone">
	<h5>Actions</h5>
	<ul class="nav nav-tabs nav-stacked">
		<li><a href="{{URL::action('ProjectDiscussionsController@index', array($thread->id))}}" data-method="delete">Delete</a></li>
	</ul>
</div>
@endif

<!--Project info and buttons-->
<div id="project-header">
	@if(Auth::user()->isAllowed('Delete-thread', $thread->user_id))
	<p class="pull-right hidden-phone">
		<a href="{{URL::action('ProjectDiscussionsController@index', array($thread->id))}}" data-method="delete" class="btn">Delete</a>
	</p>
	@endif
	<div class="page-header">
		<h4 thread-title="{{$thread->id}}"> {{$thread->title}}</h4>
	</div>
</div>
<div class="clearfix"></div>

<!--Top navigation-->
{{$comments->links()}}
<!--/Top navigation-->

@if($page < 1 OR empty($page))
<!-- First post-->
<div>
	<div class="user pull-left" >
		<a href="{{$thread->user->url()}}"><img src="{{$thread->user->avatarUrl()}}" class="img-polaroid" height="60px" width="60px"></a>
	</div>
	<div  class="post" >
		<!--Information-->
		<p class="muted pull-left"><a class="disabled" href="{{$thread->user->url()}}" >{{$thread->user->fullName()}}</a></p>
		<p class="muted pull-right">{{$thread->createdAt()}}</p>
		<div class="clearfix"></div>
		<p id="comment{{$thread->id}}" style="min-height: 50px">{{nl2br($thread->content)}}</p>
		<ul class="nav nav-pills pull-right">
			@if (Auth::user()->id == $thread->user->id)
			@if(Auth::user()->isAllowed('edit-thread', $thread->user_id))
			<li><a thread-id="{{$thread->id}}" class="edit" href="#"><i class="icon-pencil"></i> Edit</a></li>
			@endif
			@else
			<li><a href="#reply"><i class="icon-share-alt"></i> Reply</a></li>
			@endif
		</ul>
	</div>
</div>
<hr style="margin:0 0 5px 0">
<!-- /First post-->
@endif

@foreach ($comments as $comment)
<div>
	<div class="user pull-left" >
		<a href="{{$comment->user->url()}}"><img src="{{$comment->user->avatarUrl()}}" class="img-polaroid" height="60px" width="60px"></a>
	</div>
	<div  class="post" >
		<!--Information-->
		<p class="muted pull-left"><a class="disabled" href="{{$comment->user->url()}}" >{{$comment->user->fullName()}}</a></p>
		<p class="muted pull-right">{{$comment->createdAt()}}</p>
		<div class="clearfix"></div>
		<p id="comment{{$comment->id}}" style="min-height: 50px">{{nl2br($comment->content)}}</p>
		<ul class="nav nav-pills pull-right">
			@if (Auth::user()->id == $comment->user->id)
			<li><a comment-id="{{$comment->id}}" class="edit" href="#"><i class="icon-pencil"></i> Edit</a></li>
			@else
			<li><a href="#reply"><i class="icon-share-alt"></i> Reply</a></li>
			@endif
		</ul>
	</div>
</div>
<hr style="margin:0 0 5px 0">
@endforeach
<!--/ Comments-->

<!-- Bottom navigation-->
{{$comments->links()}}
<!-- /Bottom navigation-->

<!-- REPLY-->
<div class="row-fluid">
	<div class="reply user" >
		<img src="{{Auth::user()->avatarUrl()}}" class="img-polaroid">
	</div>
	<div  class="post" >
		<form id="reply" method="POST" action="{{URL::to('comment/submit')}}" accept-charset="utf-8">
			<textarea placeholder="Type your reply here" class="input-block-level" rows="6" name="comment"></textarea>
			<input type="hidden" name="type" value="{{$commentable_type}}">
			<input type="hidden" name="type_id" value="{{isset($commentable_id) ? $commentable_id : Request::segment(4)}}">
			<div class="pull-right">
				<button class="btn">Post Reply</button>
			</div>
		</form>
	</div>
</div>
<!--/ REPLY-->
@stop