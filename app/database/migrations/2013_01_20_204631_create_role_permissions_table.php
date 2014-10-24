<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_permissions', function($table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned()->index();
			$table->string('name');
			$table->boolean('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_permissions');
	}

}