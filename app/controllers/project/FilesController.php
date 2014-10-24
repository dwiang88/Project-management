<?php

class ProjectFilesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function index(Project $project)
	{
		$this->website->title = "Files list"; //web title

		$files = $project->files()->with('user');

		//if the user selects to show his files
		if(Input::get('my_files')){
			$files->where('user_id', Auth::user()->id);
		}

		//if the user selects to show his files
		if(Input::get('search')){
			$files->Where(function($query)
			{
				$query->where('title', 'LIKE', '%'.Input::get('search').'%')
					->OrWhere('description', 'LIKE', '%'.Input::get('search').'%');
			});
		}

		//make ordering
		$type = Input::get('type') == 'asc'? 'asc': 'desc';
		switch (Input::get('order_by'))
		{
			case "date":
				$files->orderBy('created_at', $type);
				break;
			case "title":
				$files->orderBy('title', $type);
				break;
			default:
				//default will be the newest
				$files->orderBy('created_at', 'desc');
		}

		$files = $files->paginate(15); //15 results

		//change the links from the url
		$files
			->appends('order_by', 	 Input::get('order_by'))
			->appends('type', 		 Input::get('type'))
			->appends('search', 	 Input::get('search'))
			->appends('my_files', 	 Input::get('my_files'));

		$data = array(
			'files' => $files,
			'type' => $type,
			'order_by' => Input::get('order_by'),
			'search' => Input::get('search'),
			'my_files' => Input::get('my_files'),
		);

		View::share('project', $project);
		$this->layout->content = View::make('projects.files.files-list')->with($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param Project $project
	 * @return Response
	 */

	public function create(Project $project)
	{
		$this->website->title = "Upload new file"; //web title
		Auth::user()->isAllowed('create-file', false, true);
		$data = array();
		View::share('project', $project);
		$this->layout->content = View::make('projects.files.file-create')->with($data);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function store(Project $project)
	{
		Auth::user()->isAllowed('create-file', false, true);
		$upload_path = 'uploads/files/';

		$rules = array(
			'file' => 'required|max:10240' //size 10mb
		);

		//Validation
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectFilesController@index', array($project->id))->withErrors($validator);
		}

		$file = Input::file('file');
		$org_file_name = $file->getClientOriginalName();
		//for now leaving the name as the original file
		$new_file_name = $org_file_name;
		//checking if there is file with the same file name
		if(File::exists($upload_path.$org_file_name))
		{
			//creating a new file name
			$i = 1;
			while (File::exists($upload_path.$i.$org_file_name)) {
				$i++;
			}
			$new_file_name = $i.$org_file_name;
		};

		$fileDB = new FileDB;
		$fileDB->fileable_type = 'Project';
		$fileDB->fileable_id = $project->id;
		$fileDB->user_id = Auth::user()->id;
		$fileDB->name = $new_file_name;
		//if the file title was not set we will name it as new file name
		$fileDB->title = Input::get('title') ? Input::get('title'): $new_file_name;
		$fileDB->description = Input::get('description');
		$fileDB->mime_type = $file->getClientOriginalExtension();
		$fileDB->size = $file->getSize();
		$fileDB->save();

		$file->move($upload_path, $new_file_name);

		return Redirect::back()->with('file_created', TRUE);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param Project $project
	 * @param FileDB $file
	 * @return Response
	 */
	public function show(Project $project,FileDB $file)
	{
		$this->website->title = $file->title; //web title

		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $file->fileable_id){
			App::abort(404);
		}

		$comments = $file
			->comments()
			->orderBy('created_at', 'asc')
			->with('user')
			->paginate('10');

		$data = array(
			'comments' 		   => $comments,
			'file'			   => $file,
			'commentable_type' => 'FileDB',
		);

		View::share('project', $project);
		$this->layout->content = View::make('projects.files.file-index')->with($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Project $project
	 * @param FileDB $file
	 * @return Response
	 */
	public function edit(Project $project,FileDB $file)
	{
		$this->website->title = "Edit -".$file->title; //web title
		Auth::user()->isAllowed('edit-file', $file->user_id, true);
		View::share('project', $project);
		$this->layout->content = View::make('projects.files.file-edit')->with('file', $file);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Project $project
	 * @param FileDB $fileDB
	 * @return Response
	 */
	public function update(Project $project,FileDB $fileDB)
	{
		Auth::user()->isAllowed('edit-file', $fileDB->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $fileDB->fileable_id){
			App::abort(404);
		}

		$rules = array(
			'file' => 'max:10240' //size 10mb
		);

		//Validation
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::action('ProjectFilesController@edit', array($project->id))->withErrors($validator);
		}

		$file = Input::file('file');

		//for now leaving the name as the original file
		 $new_file_name = $fileDB->name;
		// if file was updated
		if($file){
			$new_file_name = $file->getClientOriginalName();
			$fileDB->mime_type = $file->getClientOriginalExtension();
			$fileDB->size = $file->getSize();
			$file->move('uploads/files/', $new_file_name);
		}


		//if the file title was not set we will name it as new file name
		$fileDB->title = is_null(Input::get('title'))? $new_file_name : Input::get('title');
		$fileDB->description = Input::get('description');
		$fileDB->save();

		return Redirect::action('ProjectFilesController@show', array($project->id, $fileDB->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Project $project
	 * @param FileDB $file
	 * @return Response
	 */
	public function destroy(Project $project,FileDB $file)
	{
		Auth::user()->isAllowed('delete-file', $file->user_id, true);
		//check if the page is requested from same project
		//if no throw 404 error
		if($project->id != $file->fileable_id){
			App::abort(404);
		}

		$file->comments()->delete();
		$file->delete();
	}

}