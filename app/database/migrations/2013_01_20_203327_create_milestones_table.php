<?php

use Illuminate\Database\Migrations\Migration;

class CreateMilestonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('milestones', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->integer('project_id')->unsigned()->index();
			$table->string('title');
			$table->text('description')->nullable();
			$table->boolean('archived')->index();
			$table->enum('priority', array('Highest', 'High', 'Normal', 'Low', 'Lowest'))
				->defaul('Normal');
			$table->date('starts')->nullable();
			$table->date('ends')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('milestones');
	}

}