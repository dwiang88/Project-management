<?php

class AdminController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$this->website->title = "Website options"; //web title
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

		$data = array(
			'title' => $title,
			'description' => $description,
			'footer' => $footer
		);

		$this->layout->content = View::make('admin.admin-index')->with($data);
	}

	/**
	 * Save content
	 *
	 * @return Response
	 */
	public function index_post()
	{
		$rules = array(
			'logo' => 'mimes:jpeg' //size 1mb
		);


		//Validation
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			// The given data did not pass validation
			//setting custom formatting for the errors
			$validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
			return Redirect::to('admin')->withErrors($validator);
		}

		//save image and keep 100x400 size
		if(Input::file('logo')){
			$logo = Input::file('logo');
			$logo->move('img', 'logo.jpg');
			Image::make('img/logo.jpg')
				->resize(null, 100, true, false)
				->resize(400, null, true, false)
				->save();
		}

		$title = Setting::find('title');
		$title->data = Input::get('title');
		$title->save();

		$description = Setting::find('description');
		$description->data = Input::get('description');
		$description->save();

		$footer = Setting::find('footer');
		$footer->data = Input::get('footer');
		$footer->save();

		return Redirect::to('admin')->with('data_changed', True);
	}


}