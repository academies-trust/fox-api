<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('claims', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('device_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->timestamp('incident_at');
			$table->string('type',10);
			$table->text('details');
			$table->integer('status_id')->unsigned();
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
		Schema::drop('claims');
	}

}
