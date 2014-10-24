<?php

class ProjectTasksController extends BaseController {

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
		$this->website->title = "Task list"; //web title
		$tasks = $project
			->tasks()
			->where('finished', Input::get('finished')? 1: 0)
			->with('user');

		$milestone_id = Input::get('milestone');
		if($milestone_id){
			$tasks->where('milestone_id', $milestone_id);

			//count for the selected milestone
			$tasks_left =  $project
				->tasks()
				->where('finished', 0)
				->where('milestone_id', $milestone_id)
				->count();

			$tasks_finished = $project
				->tasks()
				->where('finished', 1)
				->where('milestone_id', $milestone_id)
				->count();
		}

		//check if the count was created if no count it for all milestones
		if(!isset($tasks_left)){
			$tasks_left = $project->tasks()->where('finished', 0)->count();
			$tasks_finished = $project->tasks()->where('finished', 1)->count();
		}

		//if the user selects to show his tasks
		if(Input::get('my_tasks')){
			$tasks
				->Join('assignments', 'assignments.assignable_id', '=', 'tasks.id')
				->Select()->addSelect('tasks.id')->addSelect('tasks.user_id')
				->where('assignable_type', 'Task')
				->where('assignments.user_id', Auth::user()->id);
		}

		//if the user selects to show his tasks
		if(Input::get('search')){
			$tasks->Where(function($query)
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
				$tasks->orderBy('ends', $type);
				break;
			case "date":
				$tasks->orderBy('created_at', $type);
				break;
			case "priority":
				$tasks->orderBy('priority', $type);
				break;
			default:
				//default will be the newest
				$tasks->orderBy('created_at', 'desc');
		}

		$tasks = $tasks->paginate(15); //15 results

		//change the links from the url
		$tasks
			->appends('order_by', 	   Input::get('order_by'))
			->appends('type', 		   Input::get('type'))
			->appends('search', 	   Input::get('search'))
			->appends('finished', 	   Input::get('finished'))
			->appends('my_milestones', Input::get('my_milestones'));

		$data = array(
			'tasks_finished' => $tasks_finished,
			'tasks_left' 	 => $tasks_left,
			'milestones'	 => $project->milestones,
			'tasks' 		 => $tasks,
			'project' 	     => $project,
			'search'		 => Input::get('search'),
			'order_by'		 => Input::get('order_by'),
			'my_tasks'		 => Input::get('my_tasks'),
			'type'			 => $type,
			'input_milestone'=> Input::get('milestone'),
			'finished'		 => Input::get('finished'),
		);

		$this->layout->content = View::make('projects.tasks.tasks-list')->with($data);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function create(Project $project)
	{
		$this->website->title = "Create new Task"; //web title

		Auth::user()->isAllowed('create-task',false, true);
		$milestones = $project
			->milestones()
			->select('id', 'title')
			->get();

		$data = array(
			'milestones' => $milestones,
			'project' => $project,
		);
		$this->layout->content = View::make('projects.tasks.task-create')->with($data);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function store(Project $project)
	{
		Auth::user()->isAllowed('create-task', false, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectTasksController@create', array($project->id))->withErrors($validator)->withInput();
		}

		$task = new Task;
		$task->title = Input::get('title');
		$task->project_id = $project->id;
		$task->milestone_id = Input::get('milestone');
		$task->description = Input::get('description');
		$task->user_id = Auth::user()->id;
		$task->priority = Input::get('priority');
		if(Input::get('starts')){
			$task->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$task->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		$task->save();

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
			$assignment->assignable_id = $task->id;
			$assignment->assignable_type = 'Task';
			$assignment->user_id = $user;
			$assignment->assigned_at = $assign_time;
			$assignment->save();
		}

		Return Redirect::action('ProjectTasksController@index', array($project->id))->with('milestone_created', TRUE);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param Project $project
	 * @param Task $task
	 * @return Response
	 */
	public function show(Project $project, Task $task)
	{
		$this->website->title = $task->title; //web title
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $task->project_id){
			App::abort(404);
		}

		$comments = $task
			->comments()
			->orderBy('created_at', 'asc')
			->with('user')
			->paginate('10');

		$assigned_users = $task->assignments()->with('user')->get();
		View::share('project', $project);
		$data = array(
			'task' 			   => $task,
			'creator'		   => $task->user,
			'comments' 		   => $comments,
			'commentable_type' => 'Task',
			'milestone' 	   => $task->milestone,
			'assigned_users'   => $assigned_users,
		);

		$this->layout->content = View::make('projects.tasks.task-index')->with($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Project $project
	 * @param Task $task
	 * @return Response
	 */
	public function edit(Project $project, Task $task)
	{
		Auth::user()->isAllowed('edit-task', $task->user_id, true);

		$this->website->title = "Edit - ".$task->title; //web title

		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $task->project_id){
			App::abort(404);
		}

		//get milestones
		$milestones = $project
			->milestones()
			->select('id', 'title')
			->get();

		$assignments = $task->assignments()->get(); //get the current users
		$assignments_id = array();
		foreach($assignments as $assignment){
			$assignments_id[] = $assignment->user_id;
		}

		$data = array(
			'milestones' => $milestones,
			'task' => $task,
			'project' => $project,
			'assigned_users' => implode(",", $assignments_id),
		);

		$this->layout->content = View::make('projects.tasks.task-edit')->with($data);


	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Project $project
	 * @param Task $task
	 * @return Response
	 */

	public function update(Project $project, Task $task)
	{
		Auth::user()->isAllowed('edit-task', $task->user_id, true);
		//Validation
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()) {
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectTasksController@edit', array($project->id, $task->id))
				->withErrors($validator)
				->withInput();
		}

		$task->title = Input::get('title');
		$task->description = Input::get('description');
		$task->project_id = $project->id;
		$task->milestone_id = Input::get('milestone');
		$task->priority = Input::get('priority');
		if(Input::get('starts')){
			$task->starts = DateTime::createFromFormat('d-m-Y', Input::get('starts'));
		}
		if(Input::get('ends')){
			$task->ends = DateTime::createFromFormat('d-m-Y', Input::get('ends'));
		}
		if (Input::get('finished')) {
			$project->finished = 1;
		}
		$task->save();

		$assignments = $task->assignments()->get(); //get the current users

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
				$new_assignment->assignable_id = $task->id;
				$new_assignment->assignable_type = 'Task';
				$new_assignment->user_id = $user;
				$new_assignment->assigned_at = $datetime;
				$new_assignment->save();
			}
		}

		Return Redirect::action('ProjectTasksController@show', array($project->id, $task->id));

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Project $project
	 * @param Task $task
	 * @return Response
	 */
	public function destroy(Project $project, Task $task)
	{
		Auth::user()->isAllowed('delete-task', $task->user_id, true);
		$task->assignments()->delete();
		$task->comments()->delete();
		$task->delete();
	}

	public function state(Project $project, Task $task)
	{
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $task->project_id){
			App::abort(404);
		}

		//check what is the current state of task
		if($task->finished)
			$task->finished = 0;
		else
			$task->finished = 1;

		$task->save();
	}

}
