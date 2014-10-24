<?php

use Illuminate\Database\Migrations\Migration;

class UpdateLayoutOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('layout_orders', function($table)
		{
			//Keys and indexes
			$table->foreign('layout_module_id')->references('id')->on('layout_modules');
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
		Schema::table('layout_orders', function($table)
		{
			//
		});
	}

}