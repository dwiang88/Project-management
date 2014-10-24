<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
    protected $layout = 'layouts.master';
	protected $website;


	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

		//get settings data
		$settings = Setting::all();
		foreach($settings as $setting){
			if($setting->name == 'title'){
				$title = $setting->data;
			}
			if($setting->name == 'description'){
				$description = $setting->data;
			}
			if($setting->name == 'footer'){
				$footer = $setting->data;
			}
		}

		//Default values for the website
		$this->website = new stdClass();
		$this->website->title = 'Project management';
		$this->website->slogan = $title;
		$this->website->complete_title = $this->website->title." - ".$title;
		$this->website->description = $description;
		$this->website->footer = $footer;

		View::share('website', $this->website);
	}

}