<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('marking_scheme_id')->unsigned();
			$table->foreign('marking_scheme_id')->references('id')->on('marking_schemes');
			$table->timestamp('due_at');
			$table->integer('group_id')->unsigned();
			$table->foreign('group_id')->references('id')->on('groups');
			$table->boolean('allow_comments');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
