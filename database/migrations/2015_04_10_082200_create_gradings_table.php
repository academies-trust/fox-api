<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gradings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('marking_scheme_id')->unsigned();
			$table->string('name', 50);
			$table->integer('grade')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gradings');
	}

}
