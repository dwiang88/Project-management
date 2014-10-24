@extends('layouts.master')
@section('content')
<div class="row-fluid">
    <legend>Create Page</legend>
    @include('layouts.errors')
    <!-- from start -->
    <form id="form" method="post" action="{{URL::action('ProjectPagesController@store', array(Request::segment(2)))}}">
        <fieldset>
            <div class="control-group {{$errors->has('title')  ? 'error' : ''}}">
                <label for="input">Project title</label>
                <input id="input" type="text" class="input-block-level" name="title" value="{{Input::old('title')}}">
                {{$errors->first('title')}}
            </div>

            <div class="control-group {{$errors->has('content')  ? 'error' : ''}}">
                <label for="content">Content</label>
                <textarea id="content" rows="6" name="content" class="input-block-level">{{Input::old('content')}}</textarea>
                {{$errors->first('content')}}
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

        </fieldset>
    </form>
    <!-- end from -->
</div>
@stop