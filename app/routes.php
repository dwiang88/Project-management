<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Filter if user is registered
Route::group(array('before' => 'auth'), function()
{
    // Model bindings
    Route::model('projects', 'Project');
    Route::model('pages', 'Page');
    Route::model('users', 'User');
	Route::model('milestones', 'Milestone');
	Route::model('tasks', 'Task');
	Route::model('files', 'FileDB');
	Route::model('discussions', 'Thread');
	Route::model('dashboardOrders', 'DashboardOrder');

	//home - Dashboard
    Route::get('home', 'HomeDashboardController@index');
    Route::post('home', 'HomeDashboardController@update');
	Route::delete('home/{dashboardOrders}', 'HomeDashboardController@delete');
	Route::delete('projects/{projects}/{dashboardOrders}', 'HomeDashboardController@delete_project');
	Route::post('dashboard/update', 'HomeDashboardController@update_module');
	Route::post('dashboard/new_module', 'HomeDashboardController@create_module');

	//Users
	Route::post('users/ajax', 'HomeUsersController@ajax'); //brings ajax
	Route::resource('users', 'HomeUsersController');

	//User settings
	Route::get('settings', 'HomeUsersController@PersonalSettings');
    Route::get('settings/personal', 'HomeUsersController@PersonalSettings');
    Route::post('settings/personal', 'HomeUsersController@PersonalSettings_post');
    Route::get('settings/contact', 'HomeUsersController@ContactSettings');
    Route::post('settings/contact', 'HomeUsersController@ContactSettings_post');
    Route::get('settings/password', 'HomeUsersController@PasswordSettings');
    Route::post('settings/password', 'HomeUsersController@PasswordSettings_post');

	//Comments
    Route::post('comment/submit', 'CommentsController@submit');
    Route::post('comment/edit', 'CommentsController@edit');

	//Other project routes
	Route::post('projects/{projects}/tasks/{tasks}/state', 'ProjectTasksController@state');

	//Project routes and sub-routes
    Route::resource('projects', 'HomeProjectsController');
    Route::resource('projects.tasks', 'ProjectTasksController');
    Route::resource('projects.milestones', 'ProjectMilestonesController');
    Route::resource('projects.discussions', 'ProjectDiscussionsController');
    Route::resource('projects.files', 'ProjectFilesController');
    Route::resource('projects.pages', 'ProjectPagesController');
    Route::resource('projects.members', 'ProjectMembersController',
		array('only' => array('index')));

	//only users with administration role are able to see this route
	Route::group(array('before' => 'admin'), function()
	{
		Route::get('admin', 'AdminController@index');
		Route::post('admin', 'AdminController@index_post');
	});
});

//Login & Logout
Route::get('/', 'AuthController@index');
Route::post('/', 'AuthController@store');
Route::get('logout', 'AuthController@logout');
Route::get('password/remind', 'AuthController@remind');
Route::post('password/remind', 'AuthController@remindPost');
Route::get('password/reset/{token}', 'AuthController@reset');
Route::post('password/reset/{token}', 'AuthController@resetPost');

/*
 * ERROR 404
 */
App::missing(function($exception)
{
    return View::make('error.404');
});

App::error(function($exception)
{
//	//if error is 403
//	if($exception->getstatusCode() == 403){
//		return View::make('error.404');
//
//	}

});