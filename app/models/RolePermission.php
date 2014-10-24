<?php

class RolePermission extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_permissions';
	public $timestamps = false;


	public function Role()
    {
        return $this->belongsTo('Role');
    }

}