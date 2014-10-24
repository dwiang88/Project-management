<?php

class HomeDashboardController extends BaseController
{

    public function index()
    {

		$this->website->title = "Home"; //web title

		//Getting the data with all the sections and locations
        $raw_modules = DashboardOrder::WhereNull('project_id')
            ->OrderBy('section')
            ->OrderBy('position')
            ->with('DashboardModule')
            ->get();

        /*
         * It is probably more resource intense to make 3 requests to db.
         *  this for loop will make 3 variable ($top,$left,$right)
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

        //Getting data ready for sending, to views
        $data = array(
            'top_modules' => $top_modules,
            'left_modules' => $left_modules,
            'right_modules' => $right_modules,
        );

        /*Project::find(1)->toArray();*/
        $this->layout->content = View::make('home.dashboard')->with($data);
    }

    /**
     * Storing all the data from dashboard home
     * @return Response
     */
    public function update()
    {
		Auth::user()->isAllowed('edit-module',false, true);
		if (Request::ajax())
        {
            $module_orders = Input::all(); //data

            //loop through all of the changed orders of sections
            foreach ($module_orders as $section_name => $orders) {

                $count = 1; //count to remember the order
                //loop through particular section order
                foreach ($orders as $order) {
                    //updating records
                    //(this probably could be done more efficiently using 1 query)
                    $new_order = DashboardOrder::find($order);
                    $new_order->section = $section_name;
                    $new_order->position = $count;
                    $new_order->save();
                    $count++;
                }
            }
        }

        //if it is not AJAX request bring it back home index
        return Redirect::to('home');
    }

	/**
	 * Updates module data
	 */
	public function update_module()
	{
		Auth::user()->isAllowed('edit-module',false, true);

		$module = DashboardModule::find(Input::get('id'));
		$module->title = Input::get('title');
		$module->content = Input::get('content');
		$module->save();
	}

	/**
	 * Creates new module and order
	 * @return Response
	 */
	public function create_module()
	{
		Auth::user()->isAllowed('create-module', false, true);

		$module = new DashboardModule;
		$module->title = Input::get('title');
		$module->content = Input::get('content');
		$module->save();

		$module_order = new DashboardOrder;
		$module_order->dashboard_module_id = $module->id;
		if(Input::get('project_id')){
			$module_order->project_id = Input::get('project_id');
		}
		$module_order->section = strtolower(Input::get('location'));
		$module_order->position = 0;
		$module_order->save();

		return Redirect::back();
	}

	/**
	 * Deletes dashboard order
	 * @param DashboardOrder $dashboardOrder
	 */
	public function delete(DashboardOrder $dashboardOrder)
	{
		Auth::user()->isAllowed('delete-module', false, true);
		$dashboardOrder->delete();

	}

	/**
	 * Deletes dashboard order in projects
	 *
	 * @param Project $project
	 * @param DashboardOrder $dashboardOrder
	 */
	public function delete_project(Project $project,DashboardOrder $dashboardOrder)
	{
		Auth::user()->isAllowed('delete-module', false, true);
		$dashboardOrder->delete();

	}
}