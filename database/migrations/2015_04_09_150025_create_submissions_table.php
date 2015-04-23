<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->foreign('owner_id')->references('id')->on('users');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');
			$table->integer('feedback_id')->unsigned()->references('id')->on('feedback')->nullable();
			$table->timestamp('read_at')->nullable();
			$table->timestamp('submitted_at')->nullable();
			$table->timestamp('marked_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('submissions');
	}

}
