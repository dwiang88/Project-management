<?php

class ProjectPagesController extends BaseController {


    /**
     * Display a listing of the resource.
     *
     * @param Project $project
     * @return Response
     */
    public function index(Project $project)
    {
		$this->website->title = "Page list"; //web title
		$pages = $project->pages()->with('user');

		//if the user selects to show his pages
		if(Input::get('my_pages')){
			$pages->where('user_id', Auth::user()->id);
		}

		//search
		if(Input::get('search')){
			$pages->Where(function($query)
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
				$pages->orderBy('created_at', $type);
				break;
			case "title":
				$pages->orderBy('title', $type);
				break;
			default:
				//default will be the newest
				$pages->orderBy('created_at', 'desc');
		}

		$pages = $pages->paginate(10); //10 results

		$pages
			->appends('order_by', 	 Input::get('order_by'))
			->appends('type', 		 Input::get('type'))
			->appends('search', 	 Input::get('search'))
			->appends('my_pages', 	 Input::get('my_pages'));


		View::share('project', $project);
        $data = array(
            'pages' => $pages,
			'type' => $type,
			'order_by' => Input::get('order_by'),
			'search' => Input::get('search'),
			'my_pages' => Input::get('my_pages'),
        );

        $this->layout->content = View::make('projects.pages.pages-list')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @return Response
     */
    public function create(Project $project)
	{
		$this->website->title = "Create new Milestone"; //web title
		Auth::user()->isAllowed('create-page',false, true);

		$this->layout->content = View::make('projects.pages.page-create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Project $project
     * @return Response
     */
    public function store(Project $project)
	{
		Auth::user()->isAllowed('create-page',false, true);

		//Validation rules
        $rules = array(
            'title' =>  'required',
            'content' => 'required',
        );

        //Validation
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // The given data did not pass validation
            //setting custom formatting for the errors
            $validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
            return Redirect::action('ProjectPagesController@create',
                array($project->id))
                ->withErrors($validator)
                ->withInput();
        }

        $page = New Page;
        $page->title = Input::get('title');
        $page->content = Input::get('content');
        $page->user_id = Auth::user()->id;
        $page->project_id = $project->id;
        $page->save();

        //go back to created page
        return Redirect::action('ProjectPagesController@show',
            array($project->id, $page->id));
	}

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @param Page $page
     * @return Response
     */
    public function show(Project $project, Page $page)
	{
		$this->website->title = $page->title; //web title
		//check if the page is requested from same project
        //if no throw 404 error
        if($project->id != $page->project_id){
            App::abort(404);
        }

        $comments = $page
            ->comments()
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->paginate('10');

		View::share('project', $project);
		$data = array(
            'project' => $project,
            'page' => $page,
            'comments' => $comments,
            'commentable_type' => 'Page'
        );
        $this->layout->content = View::make('projects.pages.page-index')->with($data);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @param Page $page
     */
    public function edit(Project $project, Page $page)
	{
		Auth::user()->isAllowed('edit-page', $page->user_id, true);

		$this->website->title = "Edit - ".$page->title; //web title

		//check if the page is requested from same project
        //if no throw 404 error
        if($project->id != $page->project_id){
            App::abort(404);
        }

        $data = array(
            'page' => $page
        );
        $this->layout->content = View::make('projects.pages.page-edit')->with($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Project $project
     * @param Page $page
     * @return Response|string
     */
    public function update(Project $project, Page $page)
	{
		Auth::user()->isAllowed('edit-page', $page->user_id, true);
		//check if the page is requested from same project
        //if no throw 404 error
        if($project->id != $page->project_id){
            App::abort(404);
        }

        //Validation rules
        $rules = array(
            'title' =>  'required',
            'content' => 'required',
        );

        //Validation
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // The given data did not pass validation
            //setting custom formatting for the errors
            $validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
            return Redirect::action('ProjectPagesController@edit',
                array($project->id, $page->id))
                ->withErrors($validator)
                ->withInput();
        }

        $page->title = Input::get('title');
        $page->content = Input::get('content');
        $page->save();

        //go back to changed page
        return Redirect::action('ProjectPagesController@show',
            array($project->id, $page->id));
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @param Page $page
     * @return Response
     */
    public function destroy(Project $project, Page $page)
	{
		Auth::user()->isAllowed('delete-page', $page->user_id, true);
		//check if the page is requested from same project
        //if no throw 404 error
        if($project->id != $page->project_id){
            App::abort(303);
        }

        //if the user who didn't create it tries to change it
        if(Auth::user()->id != $page->user_id){
            App::abort(303);
        };

        Page::find($page->id)->delete(); //delete the post

        return Redirect::action('ProjectPagesController@index', array($project->id));
    }

}