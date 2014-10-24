<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
    public $timestamps = false;
	public $permissions = array(); //for cashing the permissions

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * If there is no avatar than set the default avatar
	 * @param $avatar
	 * @return string
	 */
	public function getAvatarAttribute($avatar)
    {
        //if avatar null then set the value to 'default-avatar.png'
        return is_null($avatar) ? 'default-avatar.png': $avatar;
    }

	/**
	 * Hash the password if its set new
	 * @param $password
	 */
	public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

	/**
	 * Set relationships
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function assignments()
    {
        return $this->hasMany('Assignment');
    }

	/**
	 * Returns full name
	 *
	 * @return string
	 */
	public function fullName()
	{
		return $this->first_name.' '.$this->last_name;
	}

	/**
	 * Returns full avatar url
	 *
	 * @return string
	 */
	public function avatarUrl()
	{
		return URL::to('uploads/avatars/'.$this->avatar);
	}

	/**
	 * Makes url to user
	 *
	 * @return string
	 */
	public function url()
	{
		return URL::action('HomeUsersController@show', array($this->id));
	}

	public function pages()
	{
		return $this->hasMany('Page');
	}

	public function tasks()
	{
		return $this->hasMany('Task');
	}

	public function milestones()
	{
		return $this->hasMany('Milestone');
	}
	public function projects()
	{
		return $this->hasMany('Project');
	}

	public function threads()
	{
		return $this->hasMany('Thread');
	}

	public function files()
	{
		return $this->hasMany('FileDB');
	}

	public function role()
	{
		return $this->belongsTo('Role');
	}

	public function permissions()
	{
		return RolePermission::where('role_id', $this->role_id)->get();
	}

	/**
	 * Check if user has permissions to see content
	 *
	 * @param $permission
	 * @param int $user_id
	 * @param bool $error
	 * @return bool
	 */
	public function isAllowed($permission, $user_id = 0, $error = false)
	{
		if(empty($this->permissions)){
			//get user permissions
			$this->permissions = RolePermission::
				where('role_id', $this->role_id)->
				where('type', 1)->
				lists('name');
		}

		//check if the user has permission
		foreach($this->permissions as $current_perm){
			//make all variables to improve consistency
			if(strtolower($current_perm) == strtolower($permission)){
				return true;
				break;
			}
		}

		//if the
		if(Auth::user()->id == $user_id){
			return true;
		}

		//if error is true then abort request 403
		if($error){
			App::abort(403);
		}

		return false;
	}

	/**
	 * Check if the user has the role
	 * @param $role
	 * @return mixed
	 */
	public 	function isRole($role)
	{

		return $this->role()->where('name', $role)->pluck('name');
	}

}