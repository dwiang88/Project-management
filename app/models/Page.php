<?php

class Page extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    public function user()
    {
        return $this->belongsTo('user');
    }

	public function project()
	{
		return $this->belongsTo('project');
	}

    public function comments()
    {
        return $this->morphMany('Comment', 'commentable');
    }

	public function commentsNo()
	{
		return $this->comments()->count();
	}

	public function Url($type, $project)
	{
		return URL::action('ProjectPagesController@'.$type, array( $project, $this->id));
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