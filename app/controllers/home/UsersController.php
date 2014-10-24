<?php

class HomeUsersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->website->title = "User list"; //web title

		//Get users from table and create pagination object
        $data = array(
            'users' => User::paginate(10),
        );

        $this->layout->content = View::make('users.users-list')->with($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Auth::user()->isAllowed('create-member', false, true);
		$input = Input::all();

        $rules = array(
            'email' =>  'required|email|unique:users,email',
            'first_name' => 'required',
        );

        //Validation
        $validator = Validator::make($input, $rules);

        if (!$validator->fails())
        {
            //validation passed
            $password = Str::random(9);//create random password

            $user = New User;
            $user->email = $input['email'];
            $user->first_name = $input['first_name'];
            $user->password = $password;
            //if the user specifies the
            if(!empty($input['last_name'])){
                $user->last_name = $input['last_name'];
            }
			$user->role_id = 3;
            $user->created_at = new DateTime;
            $user->save();

            //data for email message
            $data = array(
                'first_name' => $input['first_name'],
                'password' => $password,
            );

            //email message, with all the credential information
            Mail::send('emails.invite', $data, function($m)
            {
                $m->to(Input::get('email'), Input::get('first_name'))
                    ->subject( Input::get('first_name').' you were invited to project management system!');
            });
            //dd($test);
        }

        $messages = array(
            'error'   => 'Sorry! There were some problems with data.',
            'success' => 'The user was invited, he will get all the information in the email you specified.'
        );

        return Response::json(array(
            'errors'   => $validator->messages()->toArray(), // errors
            'messages' => $messages,
            'content'  => $input, // content
        ));
	}

	/**
	 * Display the specified resource.
	 *
	 * @return Response
	 */
	public function show(User $user)
	{

		$fullname = $user->first_name;
		if($user->last_name){
			$fullname = $fullname.' '.$user->last_name;
		}
		$this->website->title =  $fullname; //web title
		$this->layout->content = View::make('users.user-index')->with('user', $user);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @return Response
	 */
	public function edit(User $user)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param User $user
	 * @return Response
	 */
	public function update(User $user)
	{
		//Update user
		if(Auth::user()->isRole('Administrator')){
			$user->role_id = Input::get('role');
			$user->save();

			return Redirect::back();
		}else{
			App::error(403);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

    // ------ SETTINGS --------

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function PersonalSettings()
    {
		$this->website->title = "Personal details"; //web title
		$data = array(
            'personal' => TRUE, // for submenu
        );
        $this->layout->content = View::make('users.settings.personal')->with($data);
    }


    public function PersonalSettings_Post()
    {
        $input = Input::all();
        //making date format year-month-day
        $input['dob'] = $input['year'].'-'.$input['month'].'-'.$input['day'];

        $rules = array(
            'dob' =>  'date_format:Y-m-d',//format('Y-m-d','2011-03-23')
            'location' => 'max:255',
            'occupation' => 'max:255',
            'personal_website' => 'url',
            'about_you' => 'max:500',
            'avatar' => 'max:1024|mimes:jpeg,bmp,png,gif' //size 1mb
        );

        //Validation
        $validator = Validator::make($input, $rules);

        if ($validator->fails())
        {
            // The given data did not pass validation
            //setting custom formatting for the errors
            $validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
            return Redirect::to('settings/personal')->withErrors($validator);
        }
        else
        {
            //passed
            $user = User::find(Auth::user()->id);
            $user->dob = $input['dob'];
            $user->location = $input['location'];
            $user->occupation = $input['occupation'];
            $user->website =  $input['personal_website'];
            $user->about = $input['about_you'];

            //update avatar field if it was uploaded
            if(!empty($input['avatar']))
            {
                //get image extension
                $file_extension= Input::file('avatar')->guessExtension();
                //create new file name from Username and id (username-1.jpeg)
                $new_filename = Auth::user()->first_name.'-'.Auth::user()->id.'.'.$file_extension;
                //move new image
                Input::file('avatar')->move('uploads/avatars', $new_filename);
                $img = Image::make('uploads/avatars/'.$new_filename);
                // crop image with ratio (1:1) and resize to 100x100 pixel
                $img->grab(100)->save('uploads/avatars/'.$new_filename);

                $user->avatar = $new_filename;//set new avatar in db
            }
            $user->save(); //Update user DB

            //go back say that data was inserted
            return Redirect::to('settings/personal')->with('data_changed', TRUE);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function ContactSettings()
    {
		$this->website->title = "Contact details"; //web title
		$this->layout->content = View::make('users.settings.contact');
    }

    public function ContactSettings_post()
    {
        $input = Input::all(); // get all input data

        /**
         * Custom validation to check if the user existing password was the same
         */
        Validator::extend('valid_password', function($attribute, $value, $parameters)
        {
            return Hash::check($value, Auth::user()->password);
        });

        $rules = array(
            'email' => 'email|unique:users,email,'.Auth::user()->id.'|required_with:current_password',
            'current_password' => 'valid_password|required_with:email',
            'contact_info' => 'required_with:contact_type',
            'contact_type' => 'required_with:contact_info',
            'mobile_number' => 'alpha_dash'
        );

        //Validation
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // The given data did not pass validation
            //setting custom formatting for the errors
            $validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
            return Redirect::to('settings/contact')->withErrors($validator);
        }
        else
        {
            //getting ready to insert data
            $user = User::find(Auth::user()->id);

            //if email was set then save it
            if(!empty($input['email']))
            {
                $user->email = $input['email'];
            }

            //if contact type was set then save the data
            if(!empty($input['contact_type']))
            {
                $user->contact_type = $input['contact_type'];
                $user->contact_information = $input['contact_info'];
            }

            //save the mobile number
            $user->mobile_number = $input['mobile_number'];
            $user->save(); //insert

            //go back say that data was inserted
            return Redirect::to('settings/contact')->with('data_changed', TRUE);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function PasswordSettings()
    {
		$this->website->title = "Password details"; //web title
		$this->layout->content = View::make('users.settings.password');
    }

    public function PasswordSettings_post()
    {
        /**
         * Custom validation to check if the user existing password was the same
         */
        Validator::extend('valid_password', function($attribute, $value, $parameters)
        {
            return Hash::check($value, Auth::user()->password);
        });

        $rules = array(
            'current_password' =>  'required|valid_password',
            'new_password' =>  'required|between:6,32|confirmed',
            'new_password_confirmation' =>  'required',);

        //Validation
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // The given data did not pass validation
            //setting custom formatting for the errors
            $validator->messages()->SetFormat('<span class="help-inline"><strong>:message</strong></span>');
            return Redirect::to('settings/password')->withErrors($validator);
        }
        else
        {
            //validation passed

            //change the password
            $user = User::find(Auth::user()->id);
            $user->password = Input::get('new_password');
            $user->save();

            //go back say that data was inserted
            return Redirect::to('settings/password')->with('password_changed', TRUE);
        }


    }

	public function ajax()
	{
		//if request is ajax then return user array
		if (Request::ajax())
		{
			//check what project users
			if(Input::get('id')){
				//send the users from project
				$users = User::Join('assignments', 'assignments.user_id', '=', 'users.id')
					->where('assignable_type', 'Project')
					->where('assignable_id', Input::get('id'))
					->select('users.id', 'first_name', 'last_name', 'email')->get();
			}else{
				//send all users
				$users = User::select('id', 'first_name', 'last_name', 'email')->get();
			}

			return Response::json($users); //return ajax
		}

		return App::abort(404);
	}

}