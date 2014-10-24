<?php

class Thread extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'threads';

	/**
	 * Relations
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('user');
	}

	/**
	 * Relations
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function project()
	{
		return $this->belongsTo('Project');
	}

	/**
	 * Relations
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function comments()
	{
		return $this->morphMany('Comment', 'commentable');
	}

	/**
	 * Creates url
	 *
	 * @param $type
	 * @param $project
	 * @return string
	 */
	public function Url($type, $project)
	{
		return URL::action('ProjectDiscussionsController@' . $type, array($project, $this->id));
	}

	/**
	 * Return comment count
	 * @return mixed
	 */
	public function commentsNo()
	{
		return $this->comments()->count();
	}

	/**
	 * Returns formated day
	 * @return string
	 */
	public function createdAt()
	{
		if ($this->created_at == $this->updated_at)
			$date = 'Created at: ' . date("d-m-Y H:i", strtotime($this->created_at));
		else
			$date = 'Last edited on: ' . date("d-m-Y H:i", strtotime($this->created_at));

		return $date;
	}
}