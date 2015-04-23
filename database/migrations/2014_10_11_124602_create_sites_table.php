<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slug',10)->unique();
			$table->integer('domain_controller_id')->unsigned();
			$table->foreign('domain_controller_id')->references('id')->on('domain_controllers');
			$table->string('hex_color',6);
			$table->string('type', 10);
			$table->integer('trust_id')->unsigned();
			$table->foreign('trust_id')->references('id')->on('trusts');
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
		Schema::drop('sites');
	}

}
