<?php

use Illuminate\Database\Migrations\Migration;

class UpdateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messages', function($table)
		{
			$table->foreign('receiver_id')->references('id')->on('users');
			$table->foreign('sender_id')->references('id')->on('users');
			$table->foreign('parent_id')->references('id')->on('messages');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('messages', function($table)
		{
			//
		});
	}

}