<?php

class Milestone extends Eloquent
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'milestones';

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

	public function assignments()
	{
		return $this->morphMany('Assignment', 'assignable');
	}

	public function tasks()
	{
		return $this->hasMany('Task');
	}

	public function commentsNo()
	{
		return $this->comments()->count();
	}

	public function url($type, $project_id)
	{
		return URL::action('ProjectMilestonesController@'.$type, array($project_id, $this->id));
	}

	public function deadline()
	{
		if(!empty($this->ends))
		{
			return 'Deadline: '.date("d-m-Y", strtotime($this->ends));
		}

		return ''; //return empty string
	}

	public function tasksLeft()
	{
		$task_total = $this->tasks()->count();
		$completed_tasks = $this->tasks()->where('finished', 1)->count();
		//if there are 0 tasks just skip the maths
		if($task_total > 0){
			$task_percent = round(100*(($completed_tasks)/$task_total));
			$task_left = $task_total - $completed_tasks;
		}
		else{
			//setting it manually
			$task_left = 0;
			$task_percent = 100;
		}

		$this->task_total = $task_total;
		$this->task_left = $task_left;
		$this->task_finished = $task_total - $task_left;
		$this->task_percent = $task_percent;

		return $this;
	}
	public function daysLeft()
	{
		if ($this->starts){
			//when there is starting time
			$total = round((strtotime($this->ends) - strtotime($this->starts)) / 86400) + 1;
			$days_left = round((strtotime($this->ends) - time()) / 86400) + 1;
		} else{
			//when there is no starting time
			$total = round((strtotime($this->ends) - time()) / 86400) + 1;
			$days_left = $total;
		}

		if ($days_left == $total){
			$time_left_perc = 100;}
		else if ($days_left > 0)
			$time_left_perc = round(100*(($total - $days_left)/$total));
		else {
			$days_left= 0;
			$time_left_perc = 100;}

		//setting the values
		$this->days_total = $total;
		if ($days_left < 0)
			$days_left = 0;
		$this->days_left = $days_left;

		//if ending date is set
		$days_start = $total - $days_left;

		if ($days_start < 0) {
			$days_start = 0;
		}
		$this->days = $days_start.'/'.$total;
		if (!$this->starts){
			$this->days = $total;
		}

		//if both dates are unset
		if (!$this->starts AND !$this->ends){
			$this->days = 0;
			$time_left_perc = 0;
		}
		$this->time_left_perc = $time_left_perc;

		return $this;
	}

	public function createdAt()
	{
		if($this->created_at == $this->updated_at)
			$date = 'Created at: '.date("d-m-Y H:i", strtotime($this->created_at));
		else
			$date = 'Last edited on: '.date("d-m-Y H:i", strtotime($this->created_at));

		return $date;
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

