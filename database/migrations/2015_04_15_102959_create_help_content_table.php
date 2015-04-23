<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpContentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('help_content', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('help_id')->unsigned();
			$table->foreign('help_id')->references('id')->on('help');
			$table->string('title');
			$table->text('content');
			$table->boolean('active');
			$table->timestamp('approved_at');
			$table->integer('approved_by')->unsigned();
			$table->foreign('approved_by')->references('id')->on('users');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('help_content');
	}

}
