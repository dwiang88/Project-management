@extends('layouts.master')
@section('content')
<div class="row-fluid">
    <legend>Edit Page</legend>
    @include('layouts.errors')
    <!-- from start -->
    <form id="form" method="post" action="{{URL::action('ProjectPagesController@show', array(Request::segment(2),$page->id))}}">
        <fieldset>
            <div class="control-group {{$errors->has('title')  ? 'error' : ''}}">
                <label for="input">Project title</label>
                <input id="input" type="text" class="input-block-level" name="title" value="{{$page->title}}">
                {{$errors->first('title')}}
            </div>

            <div class="control-group {{$errors->has('content')  ? 'error' : ''}}">
                <label for="content">Content</label>
                <textarea id="content" rows="12" name="content" class="input-block-level">{{$page->content}}</textarea>
                {{$errors->first('content')}}
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <input type="hidden" name="_method" value="put" />
        </fieldset>
    </form>
    <!-- end from -->
</div>
@stop