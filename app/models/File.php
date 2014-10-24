<?php

class FileDB extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';

    public function user()
    {
        return $this->belongsTo('user');
    }

	public function fileable()
	{
		return $this->morphTo();
	}

	public function Url($type, $project)
	{
		return URL::action('ProjectFilesController@'.$type, array( $project, $this->id));
	}

	public function comments()
	{
		return $this->morphMany('Comment', 'commentable');
	}

	public function commentsNo()
	{
		return $this->comments()->count();
	}

	public function downloadUrl()
	{
		return URL::to('uploads/files/'.$this->name);
	}

	public function megabytes()
	{
		return round($this->size / (1024*1024), 2);
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