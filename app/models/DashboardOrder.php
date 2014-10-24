<?php

class DashboardOrder extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_orders';
    public $timestamps = false;


    public function DashboardModule()
    {
        return $this->belongsTo('DashboardModule');
    }

}