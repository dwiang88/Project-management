<?php

use Illuminate\Database\Migrations\Migration;

class UpdateLayoutModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('layout_modules', function($table)
		{
			//Keys and indexes
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('layout_modules', function($table)
		{
			//
		});
	}

}