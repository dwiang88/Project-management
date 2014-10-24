<?php

class ProjectMilestonesController extends BaseController {

	public function __construct()
	{
		//rules for validation
		$this->rules = array(
			'title' => 'required',
			'starts' => 'date:d-m-Y', //format('Y-m-d','23-03-2011')
			'ends' => 'date:d-m-Y', //format('Y-m-d','23-03-2011')
		);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function index(Project $project)
	{
		$this->website->title = "Milestones list"; //web title
		$milestones = $project->milestones()
			->where('archived', Input::get('archived')? 1: 0)
			->with('assignment.user')->with('user');

		//if the user selects to show his tasks
		if(Input::get('my_tasks')){
			$milestones
				->Join('assignments', 'assignments.assignable_id', '=', 'milestones.id')
				->Select()->addSelect('milestones.id')->addSelect('milestones.user_id')
				->where('assignable_type', 'Milestone')
				->where('assignments.user_id', Auth::user()->id);
		}

		//if the user selects to show his milestones
		if(Input::get('search')){
			$milestones->Where(function($query)
			{
				$query->where('title', 'LIKE', '%'.Input::get('search').'%')
					->OrWhere('description', 'LIKE', '%'.Input::get('search').'%');
			});
		}

		//make ordering
		$type = Input::get('type') == 'asc'? 'asc': 'desc';
		switch (Input::get('order_by'))
		{
			case "end_date":
				//asc (highest, desc(lowest)
				$milestones->orderBy('ends', $type);
				break;
			case "date":
				$milestones->orderBy('created_at', $type);
				break;
			case "priority":
				$milestones->orderBy('priority', $type);
				break;
			default:
				//default will be the newest
				$milestones->orderBy('created_at', 'desc');
		}

		$milestones = $milestones->with('assignments.user')->paginate(10); //10 results

		//count time left
		foreach($milestones as $milestone){
			$milestone->tasksLeft();
			$milestone->daysLeft();
		}


		//change the links from the url
		$milestones
			->appends('order_by', 	 Input::get('order_by'))
			->appends('type', 		 Input::get('type'))
			->appends('search', 	 Input::get('search'))
			->appends('archived', 	 Input::get('archived'))
			->appends('milestone', 	 Input::get('milestone'));


		$data = array(
			'milestones'	 => $milestones,
			'project' 		 => $project,
			'search'		 => Input::get('search'),
			'order_by'		 => Input::get('order_by'),
			'archived'		 => Input::get('archived'),
			'my_milestones'	 => Input::get('my_milestones'),
			'type'			 => $type,
		);
		$this->layout->content = View::make('projects.milestones.milestones-list')->with($data);
	}


	/**
	 * Show the form for creating a new resource
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function create(Project $project)
	{
		$this->website->title = "Create new milestone"; //web title
		Auth::user()->isAllowed('create-milestone', false, true);
		$data = array(
			'project' => $project,
		);
		$this->layout->content = View::make('projects.milestones.milestone-create')->with($data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Project $project
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Project $project)
	{
		Auth::user()->isAllowed('create-milestone',false, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectMilestonesController@create', array($project->id))->withErrors($validator)->withInput();
		}

		$milestone = new Milestone;
		$milestone->title = Input::get('title');
		$milestone->project_id = $project->id;
		$milestone->description = Input::get('description');
		$milestone->user_id = Auth::user()->id;
		$milestone->priority = Input::get('priority');
		if(Input::get('starts')){
			$milestone->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$milestone->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		if(Input::get('archived')){
			$milestone->archived = 1;
		}
		$milestone->save();

		//assign the ussers
		if(Input::get('users')){
			//split users to array
			$users = explode(',', Input::get('users'));
		}else{
			//set it empty if there is nothing
			$users = array();
		}

		$assign_time = new DateTime;
		foreach ($users as $user) {
			$assignment = new Assignment;
			$assignment->assignable_id = $milestone->id;
			$assignment->assignable_type = 'Milestone';
			$assignment->user_id = $user;
			$assignment->assigned_at = $assign_time;
			$assignment->save();
		}

		Return Redirect::action('ProjectMilestonesController@index', array($project->id))->with('milestone_created', TRUE);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param Project $project
	 * @param Milestone $milestone
	 * @return Response
	 */
	public function show(Project $project, Milestone $milestone)
	{
		$this->website->title = $milestone->title; //web title

		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $milestone->project_id){
			App::abort(404);
		}

		$comments = $milestone
			->comments()
			->orderBy('created_at', 'asc')
			->with('user')
			->paginate('10');

		View::share('project', $project);
		$data = array(
			'project'		   => $project,
			'milestone'		   => $milestone,
			'comments'		   => $comments,
			'commentable_type' => 'Milestone',
			'assignments'	   => $milestone->assignments()->with('user')->get(),
		);
		$this->layout->content = View::make('projects.milestones.milestone-index')->with($data);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Project $project
	 * @param Milestone $milestone
	 * @return Response
	 */
	public function edit(Project $project, Milestone $milestone)
	{
		$this->website->title = "Edit - ".$milestone->title; //web title
		Auth::user()->isAllowed('edit-milestone', $milestone->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $milestone->project_id){
			App::abort(404);
		}

		$assignments = $milestone->assignments()->get(); //get the current users
		$assignments_id = array();
		foreach($assignments as $assignment){
			$assignments_id[] = $assignment->user_id;
		}

		$data = array(
			'milestone' => $milestone,
			'project' => $project,
			'assigned_users' => implode(",", $assignments_id),
		);

		$this->layout->content = View::make('projects.milestones.milestone-edit')->with($data);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param Project $project
	 * @param Milestone $milestone
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Project $project, Milestone $milestone)
	{
		Auth::user()->isAllowed('edit-milestone', $milestone->user_id, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectMilestonesController@edit', array($project->id, $milestone->id))
				->withErrors($validator)
				->withInput();
		}

		$milestone->title = Input::get('title');
		$milestone->description = Input::get('description');
		$milestone->project_id = $project->id;
		$milestone->user_id = Auth::user()->id;
		$milestone->priority = Input::get('priority');
		if(Input::get('starts')){
			$milestone->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$milestone->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		$milestone->save();

		$assignments = $milestone->assignments()->get(); //get the current users

		//get the old users
		if(Input::get('users')){
			//split users to array
			$users = explode(',', Input::get('users'));
		}else{
			//set it empty if there is nothing
			$users = array();
		}

		//check if any of the users were removed or deleted
		foreach ($assignments as $assignment) {
			$delete = true;
			foreach ($users as $user) {
				//check if the old users exists in the database
				if ($assignment->user_id == $user) {
					$delete = false;
					break;
				}
			}

			//delete the user
			if ($delete) {
				$assignment->delete();
			}
		}

		//add new assignments if the user is not found in list
		$datetime = new DateTime; //create all the users at the same time
		foreach ($users as $user) {
			$update = true;
			foreach ($assignments as $assignment) {
				if ($assignment->user_id == $user) {
					$update = false;
					break;
				}
			}

			//if the count the same that means the id was not in the database
			if ($update) {
				$new_assignment = new Assignment;
				$new_assignment->assignable_id = $milestone	->id;
				$new_assignment->assignable_type = 'Milestone';
				$new_assignment->user_id = $user;
				$new_assignment->assigned_at = $datetime;
				$new_assignment->save();
			}
		}

		Return Redirect::action('ProjectMilestonesController@show', array($project->id, $milestone->id));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Project $project
	 * @param Milestone $milestone
	 */
	public function destroy(Project $project, Milestone $milestone)
	{
		Auth::user()->isAllowed('delete-milestone', $milestone->user_id, true);
		$milestone->assignments()->delete();
		$milestone->comments()->delete();
		$milestone->delete();
	}

}