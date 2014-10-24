<?php

class Comment extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo('user');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

	public function createdAt()
	{
		if($this->created_at == $this->updated_at)
			$date = 'Created at: '.date("d-m-Y H:i", strtotime($this->created_at));
		else
			$date = 'Last edited on: '.date("d-m-Y H:i", strtotime($this->created_at));

		return $date;
	}
}