<?php

class ProjectMembersController extends BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @param Project $project
	 * @return Response
	 */
	public function index(Project $project)
	{
		$this->website->title = "Project member list"; //web title

		$assignments = $project
			->assignments()
			->with('user')
			->paginate(10);

		View::share('project', $project);
		$this->layout->content = View::make('projects.members.members-list')
			->with('assignments', $assignments);

	}

}