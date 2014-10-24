<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password', 64);
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('mobile_number')->nullable();
			$table->string('avatar')->nullable();
            $table->string('location')->nullable();
            $table->string('occupation')->nullable();
            $table->string('website')->nullable();
            $table->string('about', 500)->nullable();
            $table->date('dob')->nullable();
			$table->enum('contact_type',
                array('AIM', 'Facebook', 'Google Talk', 'ICQ', 'Jabber', 'MSN', 'Skype', 'Twitter', 'Yahoo!'))
                ->nullable();
			$table->string('contact_information')->nullable();
			$table->integer('role_id')->default('3')->unsigned();
			$table->dateTime('last_activity');
            $table->dateTime('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}