<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments', function($table)
		{
			$table->increments('id');
			$table->string('assignable_type')->index();
			$table->integer('assignable_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
            $table->datetime('assigned_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assignments');
	}

}