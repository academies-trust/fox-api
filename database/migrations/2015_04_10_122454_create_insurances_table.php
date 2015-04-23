<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('insurances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('company')->nullable();
			$table->string('policy')->nullable();
			$table->string('contact')->nullable();
			$table->timestamp('valid_at');
			$table->timestamp('expires_at');
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
		Schema::drop('insurances');
	}

}
