<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBehaviourModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('behaviour_models', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('value');
			$table->integer('site_id')->unsigned();
			$table->foreign('site_id')->references('id')->on('sites');
			$table->boolean('positive');
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
		Schema::drop('behaviour_models');
	}

}
