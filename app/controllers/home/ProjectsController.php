<?php

class HomeProjectsController extends BaseController
{

	/**
	 * Instantiate a new instance.
	 */
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
	 * @return Response
	 */
	public function index()
	{
		$this->website->title = "Projects list"; //web title

		$projects = Project::where('archived', Input::get('archived')? 1: 0);

		//check if the user has permissions private projects
		if(Auth::user()->isAllowed('see-private-projects')){
			$projects->where('visibility', Input::get('visibility')? 0: 1);
		}else{
			$projects->where('visibility', 1);
		}

		//if the user selects to show his projects
		if(Input::get('my_projects')){
			$projects
				->Join('assignments', 'assignments.assignable_id', '=', 'projects.id')
				->Select()->addSelect('projects.id')
				->where('assignable_type', 'Project')
				->where('assignments.user_id', Auth::user()->id);
		}

		//search
		if(Input::get('search')){
			$projects->where('title', 'LIKE', '%'.Input::get('search').'%');
			$projects->OrWhere('description', 'LIKE', '%'.Input::get('search').'%');

		}

		//make ordering
		$type = Input::get('type') == 'asc'? 'asc': 'desc';
		switch (Input::get('order_by'))
		{
			case "end_date":
				//asc (highest, desc(lowest)
				$projects->orderBy('ends', $type);
				break;
			case "date":
				$projects->orderBy('created_at', $type);
				break;
			case "priority":
				$projects->orderBy('priority', $type);
				break;
			default:
				//default will be the newest
				$projects->orderBy('created_at', 'desc');
		}

		$projects = $projects->paginate('10');

		//count time left
		foreach($projects as $key => $project){
			$projects[$key]->daysLeft();
			$projects[$key]->tasksLeft();
		}

		//change the links from the url
		$projects
			->appends('order_by', 	 Input::get('order_by'))
			->appends('type', 		 Input::get('type'))
			->appends('search', 	 Input::get('search'))
			->appends('archived', 	 Input::get('archived'))
			->appends('visibility',  Input::get('visibility'))
			->appends('my_projects', Input::get('my_projects'));

		$data = array(
			'projects'    => $projects,
			'type'        => $type,
			'search'	  => Input::get('search'),
			'order_by'    => Input::get('order_by'),
			'archived'    => Input::get('archived'),
			'visibility'  => Input::get('visibility'),
			'my_projects' => Input::get('my_projects'),
		);

		$this->layout->content = View::make('projects.project-list')->with($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		Auth::user()->isAllowed('create-project', false, true);
		$this->website->title = "Create project"; //web title


		$this->layout->content = View::make('projects.project-create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Auth::user()->isAllowed('create-project', false, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::to('projects/create')->withErrors($validator)->withInput();
		}

		$project = new Project;
		$project->title = Input::get('title');
		$project->description = Input::get('description');
		$project->user_id = Auth::user()->id;
		$project->priority = Input::get('priority');
		if(Input::get('starts')){
			$project->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$project->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		if (Input::get('visibility')) {
			$project->visibility = 0;
		}
		$project->save();

		//assign the users
		//check if any user was assigned
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
			$assignment->assignable_id = $project->id;
			$assignment->assignable_type = 'Project';
			$assignment->user_id = $user;
			$assignment->assigned_at = $assign_time;
			$assignment->save();
		}

		Return Redirect::to('projects')->with('project_created', TRUE);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function show(Project $project)
	{

		$this->website->title = $project->title; //web title
		//Getting the data with all the sections and locations
		$raw_modules = DashboardOrder::Where('project_id', $project->id)
			->OrderBy('section')
			->OrderBy('position')
			->with('DashboardModule')
			->get();

		/*
		 * This for loop will make 3 variable ($top,$left,$right)
		 * */
		$top_modules   = array(); //Declaring variables
		$left_modules  = array();
		$right_modules = array();

		//Loop through all results
		foreach ($raw_modules as $module)
		{
			//look what is the position of section and assign the right object to it
			switch ($module->section)
			{
				case "top":
					$top_modules[]   = $module->ToArray();
					break;
				case "left":
					$left_modules[]  = $module->ToArray();
					break;
				case "right":
					$right_modules[] = $module->ToArray();
					break;
			}
		}

		View::share('project', $project);
		//Getting data ready for sending, to views
		$data = array(
			'top_modules'   => $top_modules,
			'left_modules'  => $left_modules,
			'right_modules' => $right_modules,
		);
		$this->layout->content = View::make('projects.project-index')->with($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function edit(Project $project)
	{
		Auth::user()->isAllowed('edit-project', $project->user_id, true);
		$this->website->title = $project->title." - Edit"; //web title
		$assignments = $project->assignments()->get(); //get the current users
		$assignments_id = array();
		foreach($assignments as $assignment){
			$assignments_id[] = $assignment->user_id;
		}

		$data = array(
			'assigned_users' => implode(",", $assignments_id),
			'project' => $project,
		);
		$this->layout->content = View::make('projects.project-edit')->with($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function update(Project $project)
	{
		Auth::user()->isAllowed('edit-project', $project->user_id, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('HomeProjectsController@edit', array($project->id))
				->withErrors($validator)
				->withInput();
		}

		$project->title = Input::get('title');
		$project->description = Input::get('description');
		$project->user_id = Auth::user()->id;
		$project->priority = Input::get('priority');
		if(Input::get('starts')){
			$project->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$project->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		if (Input::get('visibility')) {
			$project->visibility = 0;
		}
		if (Input::get('archived')) {
			$project->archived = 1;
		}
		$project->save();

		$assignments = $project->assignments()->get(); //get the current users

		//get the new users
		//check if any user was assigned
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
					break; //stop the looping
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
					break; //stop the looping
				}
			}

			//if the count the same that means the id was not in the database
			if ($update) {
				$new_assignment = new Assignment;
				$new_assignment->assignable_id = $project->id;
				$new_assignment->assignable_type = 'Project';
				$new_assignment->user_id = $user;
				$new_assignment->assigned_at = $datetime;
				$new_assignment->save();
			}
		}

		Return Redirect::action('HomeProjectsController@show', array($project->id));
	}

	/**
	 * Remove the specified resource from storage.
	 * @param Project $project
	 * @return Response
	 */
	public function destroy(Project $project)
	{
		Auth::user()->isAllowed('delete-project', $project->user_id, true);
		//delete every thing from projects
		$project->assignments()->delete();
		$project->milestones()->delete();
		$project->tasks()->delete();
		$project->pages()->delete();
		$project->delete();

	}

}