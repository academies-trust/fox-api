<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feedbacks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')->references('id')->on('users');
			$table->text('content');
			$table->boolean('allow_comments');
			$table->integer('marking_scheme_id')->unsigned()->nullable();
			$table->foreign('marking_scheme_id')->references('id')->on('marking_schemes');
			$table->integer('grade')->unsigned()->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('feedbacks');
	}

}
