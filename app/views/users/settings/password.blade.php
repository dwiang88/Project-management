@extends('layouts.master')
@section('content')
<div class="row">
    @include('users.settings.sidemenu')

    <div class="span9">
        <!-- PASSWORD-->
        <form  method="POST" action="{{URL::to('settings/password')}}" accept-charset="utf-8"  class="form-horizontal well">

            <fieldset>
                <legend>Password</legend>

                @if (Session::get('password_changed'))
                <div class="alert alert-success ">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Well done!</strong> You successfully changed your password.
                </div>
                @endif

                @if (count($errors))
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Oh snap!</strong> There were some errors.
                </div>
                @endif

                <div class="control-group {{$errors->has('current_password') ? 'error' : ''}}">
                    <label class="control-label" for="current-password">Your Existing Password:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="current-password" name="current_password"  type="password" value="">
                        {{$errors->first('current_password')}}
                        <p class="help-block">To change Password you need to enter your current password to set a new
                            password.</p>
                    </div>
                </div>
                <hr>

                <div class="control-group {{$errors->has('new_password') ? 'error' : ''}}">
                    <label class="control-label" for="new-password">New Password:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="new-password" name="new_password" type="password">
                        {{$errors->first('new_password')}}
                        <p class="help-block">Password minimum length is 6 characters.</p>
                    </div>
                </div>

                <div class="control-group {{$errors->has('new_password_confirmation')  ? 'error' : ''}}">
                    <label class="control-label" for="confirm-new-password">Confirm New Password:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="confirm-new-password" name="new_password_confirmation" type="password">
                        {{$errors->first('new_password_confirmation')}}
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
@stop