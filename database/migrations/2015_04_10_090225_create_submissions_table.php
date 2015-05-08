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
			$table->integer('feedback_id')->unsigned()->nullable();
			$table->foreign('feedback_id')->references('id')->on('feedbacks');
			$table->timestamp('read_at')->nullable();
			$table->timestamp('submitted_at')->nullable();
			$table->timestamp('marked_at')->nullable();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->timestamps();
			$table->timestamp('published_at');
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
