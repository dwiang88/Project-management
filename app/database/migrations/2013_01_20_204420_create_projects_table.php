<?php

use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index();
			$table->string('title');
			$table->text('description')->nullable();
			$table->boolean('archived')->default('0')->index();
			$table->boolean('visibility')->default('1')->index();
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
		Schema::drop('projects');
	}

}