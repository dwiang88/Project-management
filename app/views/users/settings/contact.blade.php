@extends('layouts.master')
@section('content')
<div class="row">
    @include('users.settings.sidemenu')
    <div class="span9">
        <!-- CONTACT DETAIL FORM-->
        <form method="POST" action="{{URL::to('settings/contact')}}" accept-charset="utf-8"
              class="form-horizontal well">
            @if (count($errors))
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Oh snap!</strong> There were some errors.
            </div>
            @endif

            @if (Session::get('data_changed'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Well done!</strong> Data was successfully changed.
            </div>
            @endif

            <fieldset>
                <legend>Contact detail</legend>
                <div class="control-group {{$errors->has('email') ? 'error' : ''}}">
                    <label class="control-label" for="email">Email address:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="email" name="email" type="text">
                        {{$errors->first('email')}}
                        <p class="help-block">Your current email address is <em>{{Auth::user()->email}}</em>.</p>

                    </div>
                </div>
                <div class="control-group {{$errors->has('current_password') ? 'error' : ''}}">
                    <label class="control-label" for="current-password">Current Password:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="current-password" name="current_password" type="password">
                        {{$errors->first('current_password')}}
                        <p class="help-block">To change Email you need to enter you password.</p>
                    </div>
                </div>
                <hr>

                <div class="control-group {{$errors->has('contact_type') ? 'error' : ''}}">
                    <label class="control-label" for="contact-type">Contact type:</label>

                    <div class="controls">
                        <select class="input-xlarge" id="contact-type" name="contact_type" onchange="disable_input()"  selected_value="{{Auth::user()->contact_type}}">
                            <option id=""></option>
                            <option id="AIM">AIM</option>
                            <option id="Facebook">Facebook</option>
                            <option id="Google-Talk">Google Talk</option>
                            <option id="ICQ">ICQ</option>
                            <option id="Jabber">Jabber</option>
                            <option id="MSN">MSN</option>
                            <option id="Skype">Skype</option>
                            <option id="Twitter">Twitter</option>
                            <option id="Yahoo!">Yahoo!</option>
                        </select>
                        {{$errors->first('contact_type')}}
                    </div>
                </div>

                <div class="control-group {{$errors->has('contact_info') ? 'error' : ''}}">
                    <label class="control-label" for="contact-info">Contact information:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="contact-info" name="contact_info" type="text" value="{{Auth::user()->contact_information}}">
                        {{$errors->first('contact_info')}}
                        <p class="help-block">This field is for your selected <em>Contact type</em>.</p>
                    </div>

                </div>
                <hr>
                <div class="control-group {{$errors->has('mobile_number') ? 'error' : ''}}">
                    <label class="control-label" for="mobile">Mobile number:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="mobile" name="mobile_number" type="text" value="{{Auth::user()->mobile_number}}">
                        {{$errors->first('mobile_number')}}
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