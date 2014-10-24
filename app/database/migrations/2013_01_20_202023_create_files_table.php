<?php

use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function($table)
		{
			$table->increments('id');
			$table->integer('fileable_id')->unsigned()->index();
			$table->string('fileable_type')->index();
			$table->integer('user_id')->unsigned()->index();
			$table->string('name', '500'); //default name
            $table->string('title', '500'); //user sets title for file
            $table->string('description','1000')->nullable();
            $table->string('mime_type');
			$table->integer('size');
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
		Schema::drop('files');
	}

}