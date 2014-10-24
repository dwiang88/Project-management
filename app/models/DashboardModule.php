<?php

class DashboardModule extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_modules';
    public $timestamps = false;


    public function DashboardOrder()
    {
        return $this->hasMany('DashboardOrder');
    }

}