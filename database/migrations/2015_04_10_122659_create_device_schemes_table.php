<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceSchemesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('device_schemes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('insurance_id')->unsigned();
			$table->foreign('insurance_id')->references('id')->on('insurances');
			$table->integer('site_id')->unsigned();
			$table->foreign('site_id')->references('id')->on('sites');
			$table->softDeletes();
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
		Schema::drop('device_schemes');
	}

}
