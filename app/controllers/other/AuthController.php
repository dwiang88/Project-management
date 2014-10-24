<?php

class AuthController extends BaseController {

    public function __construct()
    {
        //Users who are registered are filtered
        $this->beforeFilter('guest',array('except' => 'logout'));

        //CSRF protection on all post methods
        $this->beforeFilter('csrf',array('on' => 'post'));

    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('auth.login');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //Check if remember me option was checked
        $remember_me = Input::get('remember_me') === 'true'? true: false;

		//Validation rules
        $rules = array(
            'email'  => 'required|max:50|email',
            'password'   => 'required|min:6|max:32'
        );

        //Validation
        $validation = Validator::make(Input::all(), $rules);

        if ($validation->fails())
        {
            //Validation Fail then redirect to login page with errors and with previous input
            $errors = $validation->
                errors()->
                all('<li>:message</li>');

            return Redirect::to('/')
                ->with('errors', $errors)
                ->withInput(Input::except('password'));
        }

        //Authorization
        $credentials = array('email' => Input::get('email'), 'password' => Input::get('password'));
        if (Auth::attempt($credentials, $remember_me))
        {
            //If Login successful then
            return Redirect::to('/');
        }else{
            //go back with error
            return Redirect::to('/')
                ->withInput(Input::except('password'))
                ->with('errors', array('<li>Bad email or password</li>'));
        }

	}

    /**
     * Logout the user and redirect to login page
     *
     * @return Response
     */
	public function logout()
	{
		Auth::logout();
		return Redirect::to('/');
	}

    /**
     * Display remind page
     * @return Response
     */
    public function remind()
    {
        return View::make('auth.remind');
    }

    public function remindPost()
    {
        //Validation rules
        $rules = array(
            'email'  => 'required|max:50|email',
        );

        //Validation
        $validation = Validator::make(Input::all(), $rules);

        if (!$validation->fails())
        {
            $credentials = array('email' => Input::get('email'));
            //validation passed
            return Password::remind($credentials);
        }

        //Set error message using session flash and redirect
        Session::flash('reason', $validation->messages()->first('email', ':message'));
        Session::flash('error', TRUE);
        return Redirect::to('password/remind');
    }

    public function reset($token)
    {
        return View::make('auth.reset')->with('token', $token);
    }

    public function resetPost($token)
    {
        //get the email of user who is resetting pass
        $email = DB::table('password_reminders')
            ->where('token', $token)
            ->pluck('email');

        $credentials = array('email' => $email);

        return Password::reset($credentials, function($user, $password)
        {
            $user->password = $password;
            $user->save();

            return Redirect::to('/');
        });
    }

}