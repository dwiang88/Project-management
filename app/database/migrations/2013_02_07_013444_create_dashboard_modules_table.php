<?php

use Illuminate\Database\Migrations\Migration;

class CreateDashboardModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dashboard_modules', function($table)
		{
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dashboard_modules');
	}

}