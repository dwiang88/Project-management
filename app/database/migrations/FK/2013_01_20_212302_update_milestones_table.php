<?php

use Illuminate\Database\Migrations\Migration;

class UpdateMilestonesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('milestones', function($table)
		{
			//Keys and indexes
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('project_id')->references('id')->on('projects');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('milestones', function($table)
		{
			//
		});
	}

}