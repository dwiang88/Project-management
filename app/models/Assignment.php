<?php

class Assignment extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assignments';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('user');
    }

    public function assignable()
    {
        return $this->morphTo();
    }
}