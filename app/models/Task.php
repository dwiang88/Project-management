<?php

class Task extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    public function user()
    {
        return $this->belongsTo('User');
    }

	public function project()
	{
		return $this->belongsTo('Project');
	}

	public function milestone()
	{
		return $this->belongsTo('Milestone');
	}

	public function assignments()
	{
		return $this->morphMany('Assignment', 'assignable');
	}

	public function comments()
	{
		return $this->morphMany('Comment', 'commentable');
	}

	public function createdAt()
	{
		if($this->created_at == $this->updated_at)
			$date = 'Created at: '.date("d-m-Y H:i", strtotime($this->created_at));
		else
			$date = 'Last edited on: '.date("d-m-Y H:i", strtotime($this->created_at));

		return $date;
	}

	public function commentsNo()
	{
		return $this->comments()->count();
	}

	public function Url($type,$project_id)
	{
		return URL::action('ProjectTasksController@'.$type, array($project_id, $this->id));
	}

	public function deadline()
	{
		if(!empty($this->ends))
		{
			return date("d-m-Y", strtotime($this->ends));
		}

		return ''; //return empty string
	}

	public function priority_colored()
	{
		if($this->priority == 'Highest')
		{
			return '<span class="text-error"><strong>Highest</strong></span>';

		}
		elseif($this->priority == 'High')
		{
			return '<span class="text-warning"><strong>High</strong></span>';
		}

		return $this->priority;
	}

}