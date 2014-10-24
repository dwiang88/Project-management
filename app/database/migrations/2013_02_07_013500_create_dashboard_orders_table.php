<?php

use Illuminate\Database\Migrations\Migration;

class CreateDashboardOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dashboard_orders', function($table)
		{
            $table->increments('id');
            $table->integer('dashboard_module_id')->nullable()->unsigned()->index();
            $table->integer('project_id')->nullable()->unsigned()->index();
            $table->enum('section', array('top', 'left', 'right'));
            $table->integer('position');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dashboard_orders');
	}

}