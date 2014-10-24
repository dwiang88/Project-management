<?php

use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('project_id')->unsigned()->index();
			$table->integer('milestone_id')->nullable()->unsigned();
			$table->string('title');
			$table->text('description')->nullable();
			$table->boolean('finished')->default('0')->index();
			$table->enum('priority', array('Highest', 'High', 'Normal', 'Low', 'Lowest'))
				->defaul('Normal');
			$table->date('starts')->nullable();
			$table->date('ends')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.aa
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}