<?php

class Role extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';
	public $timestamps = false;

	public function user()
    {
        return $this->belongsTo('user');
    }

	public function permissions()
	{
		return $this->hasMany('RolePermission');
	}
}