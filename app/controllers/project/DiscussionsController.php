<?php

class ProjectDiscussionsController extends BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function index(Project $project)
	{
		$this->website->title = "Discussions list"; //web title


		$threads = $project->threads()->with('user');

		//if the user selects to show his threads
		if(Input::get('my_threads')){
			$threads->where('user_id', Auth::user()->id);
		}

		//if the user selects to show his threads
		if(Input::get('search')){
			$threads->Where(function($query)
			{
				$query->where('title', 'LIKE', '%'.Input::get('search').'%')
					->OrWhere('content', 'LIKE', '%'.Input::get('search').'%');
			});
		}

		//make ordering
		$type = Input::get('type') == 'asc'? 'asc': 'desc';
		switch (Input::get('order_by'))
		{
			case "date":
				$threads->orderBy('created_at', $type);
				break;
			case "title":
				$threads->orderBy('title', $type);
				break;
			default:
				//default will be the newest
				$threads->orderBy('created_at', 'desc');
		}

		$threads = $threads->paginate(15);

		//change the links from the url
		$threads
			->appends('order_by', 	 Input::get('order_by'))
			->appends('type', 		 Input::get('type'))
			->appends('search', 	 Input::get('search'))
			->appends('my_threads',  Input::get('my_threads'));

		$data = array(
			'threads' => $threads,
			'type' => $type,
			'order_by' => Input::get('order_by'),
			'search' => Input::get('search'),
			'my_threads' => Input::get('my_threads'),
		);


		View::share('project', $project);
		$this->layout->content = View::make('projects.discussions.discussions-list')->with($data);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param Project $project
	 * @return Response
	 */

	public function create(Project $project)
	{
		$this->website->title = "Create new Discussion"; //web title

		Auth::user()->isAllowed('create-thread', false, true);
		View::share('project', $project);
		$this->layout->content = View::make('projects.discussions.discussion-create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function store(Project $project)
	{
		Auth::user()->isAllowed('create-thread',false, true);
		$rules = array(
			'title' => 'required',
			'content' => 'required'
		);

		//Validation
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectDiscussionsController@create', array($project->id))
				->withErrors($validator)->withInput();
		}

		$thread = new Thread;
		$thread->project_id = $project->id;
		$thread->user_id = Auth::user()->id;
		$thread->title = Input::get('title');
		$thread->content = Input::get('content');
		$thread->save();

		return Redirect::action('ProjectDiscussionsController@index', array($project->id))->with('thread_created', TRUE);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param Project $project
	 * @param Thread $thread
	 * @return Response
	 */

	public function show(Project $project, Thread $thread)
	{
		$this->website->title = $thread->title; //web title
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $thread->project_id){
			App::abort(404);
		}

		$comments = $thread
			->comments()
			->orderBy('created_at', 'asc')
			->with('user')
			->paginate('10');

		$data = array(
			'page'			   => Input::get('page'),
			'comments' 		   => $comments,
			'thread'		   => $thread,
			'commentable_type' => 'Thread',
		);

		View::share('project', $project);
		$this->layout->content = View::make('projects.discussions.discussion-index')->with($data);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Project $project
	 * @param Thread $thread
	 * @return Response
	 */
	public function edit(Project $project, Thread $thread)
	{
		$this->website->title = "Edit - ".$thread->title; //web title
		Auth::user()->isAllowed('edit-thread', $thread->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $thread->project_id){
			App::abort(404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Project $project
	 * @param Thread $thread
	 * @return Response
	 */

	public function update(Project $project, Thread $thread)
	{
		Auth::user()->isAllowed('edit-thread', $thread->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $thread->project_id){
			App::abort(404);
		}

		$title = Input::get('title');
		$content = Input::get('content');

		if($title) {
			$thread->title = $title;
		}
		if($content){
			$thread->content = $content;
		}
		$thread->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Project $project
	 * @param Thread $thread
	 * @return Response
	 */

	public function destroy(Project $project, Thread $thread)
	{
		Auth::user()->isAllowed('delete-thread', $thread->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $thread->project_id){
			App::abort(404);
		}
		$thread->comments()->delete();
		$thread->delete();
	}

}