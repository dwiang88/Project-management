@extends('layouts.master')
@section('content')
<div class="row">
    @include('users.settings.sidemenu')
    <div class="span9">
        <!-- PERSONAL DETAIL FORM-->
        <form  method="POST" action="{{URL::to('settings/personal')}}" accept-charset="utf-8" enctype="multipart/form-data" class="form-horizontal well">
            <fieldset>
                <legend>Personal detail</legend>

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

                <div class="control-group {{$errors->has('avatar') ? 'error' : ''}}">
                    <label class="control-label" for="avatar">Avatar:</label>

                    <div class="controls">
                        <img src="{{Auth::user()->avatarUrl()}}" class="img-polaroid">
                        <br>
                        <input class="input-file" id="avatar" name="avatar" type="file">
                        {{$errors->first('avatar')}}
                        <p class="help-block">Avatar can be 100x100 px.</p>
                    </div>
                </div>
                <hr>
                <div class="control-group {{$errors->has('dob') ? 'error' : ''}}">
                    <label class="control-label" for="day">Date of Birth:</label>

                    <div id="date" class="controls" dob="{{Auth::user()->dob}}">
                        <select class="input-mini" name="day" id="day">
                            <option></option>
                            <option>01</option>
                            <option>02</option>
                            <option>03</option>
                            <option>04</option>
                            <option>05</option>
                            <option>06</option>
                            <option>07</option>
                            <option>08</option>
                            <option>09</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                            <option>13</option>
                            <option>14</option>
                            <option>15</option>
                            <option>16</option>
                            <option>17</option>
                            <option>18</option>
                            <option>19</option>
                            <option>21</option>
                            <option>22</option>
                            <option>23</option>
                            <option>24</option>
                            <option>25</option>
                            <option>26</option>
                            <option>27</option>
                            <option>28</option>
                            <option>29</option>
                            <option>30</option>
                            <option>31</option>
                        </select>
                        <select id="month" class="input-mini" name="month">
                            <option></option>
                            <option>01</option>
                            <option>02</option>
                            <option>03</option>
                            <option>04</option>
                            <option>05</option>
                            <option>06</option>
                            <option>07</option>
                            <option>08</option>
                            <option>09</option>
                            <option>10</option>
                            <option>11</option>
                            <option>12</option>
                        </select>
                        <select id="year" class="input-small" name="year">
                            <option></option>
                            <option>1910</option>
                            <option>1911</option>
                            <option>1912</option>
                            <option>1913</option>
                            <option>1914</option>
                            <option>1915</option>
                            <option>1916</option>
                            <option>1917</option>
                            <option>1918</option>
                            <option>1919</option>
                            <option>1920</option>
                            <option>1921</option>
                            <option>1922</option>
                            <option>1923</option>
                            <option>1924</option>
                            <option>1925</option>
                            <option>1926</option>
                            <option>1927</option>
                            <option>1928</option>
                            <option>1929</option>
                            <option>1930</option>
                            <option>1931</option>
                            <option>1932</option>
                            <option>1933</option>
                            <option>1934</option>
                            <option>1935</option>
                            <option>1936</option>
                            <option>1937</option>
                            <option>1938</option>
                            <option>1939</option>
                            <option>1940</option>
                            <option>1941</option>
                            <option>1942</option>
                            <option>1943</option>
                            <option>1944</option>
                            <option>1945</option>
                            <option>1946</option>
                            <option>1947</option>
                            <option>1948</option>
                            <option>1949</option>
                            <option>1950</option>
                            <option>1951</option>
                            <option>1952</option>
                            <option>1953</option>
                            <option>1954</option>
                            <option>1955</option>
                            <option>1956</option>
                            <option>1957</option>
                            <option>1958</option>
                            <option>1959</option>
                            <option>1960</option>
                            <option>1961</option>
                            <option>1962</option>
                            <option>1963</option>
                            <option>1964</option>
                            <option>1965</option>
                            <option>1966</option>
                            <option>1967</option>
                            <option>1968</option>
                            <option>1969</option>
                            <option>1970</option>
                            <option>1971</option>
                            <option>1972</option>
                            <option>1973</option>
                            <option>1974</option>
                            <option>1975</option>
                            <option>1976</option>
                            <option>1977</option>
                            <option>1978</option>
                            <option>1979</option>
                            <option>1980</option>
                            <option>1981</option>
                            <option>1982</option>
                            <option>1983</option>
                            <option>1984</option>
                            <option>1985</option>
                            <option>1986</option>
                            <option>1987</option>
                            <option>1988</option>
                            <option>1989</option>
                            <option>1990</option>
                            <option>1991</option>
                            <option>1992</option>
                            <option>1993</option>
                            <option>1994</option>
                            <option>1995</option>
                            <option>1996</option>
                            <option>1997</option>
                            <option>1998</option>
                            <option>1999</option>
                            <option>2000</option>
                            <option>2001</option>
                            <option>2002</option>
                            <option>2003</option>
                            <option>2004</option>
                            <option>2005</option>
                            <option>2006</option>
                            <option>2007</option>
                            <option>2008</option>
                            <option>2009</option>
                            <option>2010</option>
                        </select>
                        {{$errors->first('dob')}}
                        <p class="help-block">Day/Month/Year</p>
                    </div>
                </div>
                <hr>
                <div class="control-group {{$errors->has('location') ? 'error' : ''}}">
                    <label class="control-label" for="location">Location:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="location" name="location" type="text" value="{{Auth::user()->location}}">
                        {{$errors->first('location')}}
                    </div>
                </div>
                <div class="control-group {{$errors->has('occupation') ? 'error' : ''}}">
                    <label class="control-label" for="occupation">Occupation:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="occupation" name="occupation" type="text" value="{{Auth::user()->occupation}}">
                        {{$errors->first('occupation')}}
                    </div>
                </div>
                <div class="control-group {{$errors->has('personal_website') ? 'error' : ''}}">
                    <label class="control-label" for="personal-website">Personal website:</label>

                    <div class="controls">
                        <input class="input-xlarge" id="personal-website" name="personal_website" type="text" value="{{Auth::user()->website}}">
                        {{$errors->first('personal_website')}}
                    </div>
                </div>
                <hr>

                <div class="control-group {{$errors->has('about_you') ? 'about' : ''}}">
                    <label class="control-label" for="about">About You:</label>

                    <div class="controls">
                        <textarea class="input-xlarge" id="about" name="about_you" rows="6">{{Auth::user()->about}}</textarea>
                        {{$errors->first('about_you')}}
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